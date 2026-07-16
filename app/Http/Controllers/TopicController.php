<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\TopicView;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    // GET /topics — list all topics for the Discussions screen
   public function index(Request $request): JsonResponse
{
    $userId = $request->user()->id;

    $topics = Topic::with([
        'user:id,name',
        'group' => function ($query) {
            $query->withCount('members')
                ->with('creator:id,name');
        },
    ])
        ->withCount('messages')
        ->latest()
        ->get();

    $topics->each(function ($topic) use ($userId) {
        if ($topic->group) {
            $topic->group->is_member = $topic->group->members()
                ->where('user_id', $userId)
                ->exists();
        }
    });

    // NEW: filter out private-group topics the current user can't access
    $topics = $topics->filter(function ($topic) use ($userId) {
        if (!$topic->group) {
            return true; // general discussion, always visible
        }

        if ($topic->group->visibility === 'public') {
            return true;
        }

        // Private group — only visible to the creator or an approved member
        return $topic->group->created_by === $userId || $topic->group->is_member;
    })->values();

    return response()->json($topics);
}

    // POST /api/topics — create a new topic
    public function store(Request $request): JsonResponse
    {
        // NEW:
     $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'nullable|integer',
        'group_id' => 'nullable|integer',
        'interest_id' => 'nullable|integer|exists:user_interests,InterestID',
]);

       // NEW: if posting into a group, confirm membership first
if (!empty($validated['group_id'])) {
    $group = \App\Models\Group::findOrFail($validated['group_id']);
    $isMember = $group->members()->where('user_id', $request->user()->id)->exists();

    if (!$isMember) {
        return response()->json(['message' => 'You must be a member of this group to post here'], 403);
    }
}

        $topic = Topic::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $topic->load('user:id,name');

        return response()->json($topic, 201);
    }

    // GET /api/topics/{topic} — view a single topic
   public function show(Request $request, Topic $topic): JsonResponse
{
    $topic->load(['user:id,name', 'group:id,visibility,created_by']);

    if ($topic->group && $topic->group->visibility === 'private') {
        $userId = $request->user()->id;
        $isMember = $topic->group->created_by === $userId
            || $topic->group->members()->where('user_id', $userId)->exists();

        if (!$isMember) {
            return response()->json(['message' => 'You do not have access to this topic'], 403);
        }
    }

    return response()->json($topic->makeHidden('group'));
}

    // PUT/PATCH /api/topics/{topic} — edit a topic (author only)
    public function update(Request $request, Topic $topic): JsonResponse
    {
        if ($topic->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $topic->update($validated);

        return response()->json($topic);
    }

    // DELETE /api/topics/{topic} — remove a topic (author only)
    public function destroy(Request $request, Topic $topic): JsonResponse
    {
        if ($topic->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted']);
    }

    public function recordView(Request $request, Topic $topic): JsonResponse
{
    $view = TopicView::firstOrCreate(
        ['user_id' => $request->user()->id, 'topic_id' => $topic->id],
        ['view_count' => 0]
    );

    $view->increment('view_count');
    $view->update(['last_viewed_at' => now()]);

    return response()->json(['message' => 'View recorded']);
}

// GET /topics/{topic}/preview — public page for social crawlers & signed-out visitors
public function publicPreview(Topic $topic)
{
    $topic->load('group:id,visibility');

    $isPrivate = $topic->group && $topic->group->visibility === 'private';

    return view('topics.public-preview', [
        'topic' => $topic,
        'title' => $isPrivate ? 'Private Discussion' : $topic->title,
        'description' => $isPrivate
            ? 'This discussion is only visible to members of a private group.'
            : Str::limit(strip_tags($topic->content), 160),
        'isPrivate' => $isPrivate,
    ]);
}
}