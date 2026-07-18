<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\GroupJoinRequest;

class GroupController extends Controller
{
    // GET /groups — list all groups, with member counts
    public function index(Request $request): JsonResponse
    {
        $groups = Group::withCount('members')
            ->with('creator:id,name')
            ->latest()
            ->get();


        $groups->each(function ($group) use ($request) {
            $group->is_member = $group->members->contains('id', $request->user()->id);
        });

       // In GroupController@index, update the each() block:
$groups->each(function ($group) use ($request) {
    $group->is_member = $group->members->contains('id', $request->user()->id);
    $group->has_pending_request = $group->joinRequests()
        ->where('user_id', $request->user()->id)
        ->where('status', 'pending')
        ->exists();
});

        return response()->json($groups->makeHidden('members'));
    }

    // POST /groups — create a new group
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
        ]);

        $group = Group::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        // Creator automatically becomes a member
        $group->members()->attach($request->user()->id);

        $group->loadCount('members');
        $group->load('creator:id,name');

        return response()->json($group, 201);
    }

    // Add this method to app/Http/Controllers/GroupController.php

    public function leave(Request $request, Group $group): JsonResponse
    {
        $isCreator = $group->created_by === $request->user()->id;

        if ($isCreator) {
            return response()->json(['message' => 'Group creator cannot leave their own group'], 403);
        }

        $group->members()->detach($request->user()->id);

        $group->loadCount('members');

        return response()->json($group);
    }

    // POST /groups/{group}/join — current user joins a group
    public function join(Request $request, Group $group): JsonResponse
    {
        $alreadyMember = $group->members()
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyMember) {
            return response()->json(['message' => 'Already a member'], 409);
        }

        $group->members()->attach($request->user()->id);

        $group->loadCount('members');

        return response()->json($group);
    }


  public function requestToJoin(Request $request, Group $group): JsonResponse
{
    $userId = $request->user()->id;

    $alreadyMember = $group->members()->where('user_id', $userId)->exists();
    if ($alreadyMember) {
        return response()->json(['message' => 'Already a member'], 409);
    }

    if ($group->visibility === 'public') {
        $group->members()->attach($userId);
        $group->loadCount('members');
        return response()->json($group);
    }

    // Private group — create or update a join request instead of joining directly
    $existingRequest = $group->joinRequests()->where('user_id', $userId)->first();

    if ($existingRequest && $existingRequest->status === 'pending') {
        return response()->json(['message' => 'Request already pending'], 409);
    }

    if ($existingRequest) {
        $existingRequest->update(['status' => 'pending']);
    } else {
        $group->joinRequests()->create(['user_id' => $userId, 'status' => 'pending']);
    }

    return response()->json(['message' => 'Join request sent']);
}

    // GET /groups/{group} — view a single group (used for group_details screen)
    public function show(Request $request, Group $group): JsonResponse
    {
        $group->loadCount('members');
        $group->load('creator:id,name');
        $group->is_member = $group->members()->where('user_id', $request->user()->id)->exists();

        return response()->json($group);
    }

    // GET /groups/{group}/requests — list pending requests (admin only)
public function pendingRequests(Request $request, Group $group): JsonResponse
{
    if ($group->created_by !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $requests = $group->joinRequests()
        ->where('status', 'pending')
        ->with('user:id,name,email')
        ->get();

    return response()->json($requests);
}

// POST /group-requests/{groupJoinRequest}/approve
public function approveRequest(Request $request, GroupJoinRequest $groupJoinRequest): JsonResponse
{
    if ($groupJoinRequest->group->created_by !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $groupJoinRequest->update(['status' => 'approved']);
    $groupJoinRequest->group->members()->attach($groupJoinRequest->user_id);

    return response()->json(['message' => 'Request approved']);
}

// POST /group-requests/{groupJoinRequest}/reject
public function rejectRequest(Request $request, GroupJoinRequest $groupJoinRequest): JsonResponse
{
    if ($groupJoinRequest->group->created_by !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $groupJoinRequest->update(['status' => 'rejected']);

    return response()->json(['message' => 'Request rejected']);
}



// Add to GroupController.php

public function myPendingRequests(Request $request): JsonResponse
{
    $userId = $request->user()->id;

    $requests = GroupJoinRequest::whereHas('group', function ($query) use ($userId) {
            $query->where('created_by', $userId);
        })
        ->where('status', 'pending')
        ->with(['user:id,name,email', 'group:id,name'])
        ->latest()
        ->get();

    return response()->json($requests);
}
}
