<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    return response()->json($groups->makeHidden('members'));
}

    // POST /groups — create a new group
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
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

    // GET /groups/{group} — view a single group (used for group_details screen)
     public function show(Request $request, Group $group): JsonResponse
{
    $group->loadCount('members');
    $group->load('creator:id,name');
    $group->is_member = $group->members()->where('user_id', $request->user()->id)->exists();

    return response()->json($group);
}
}