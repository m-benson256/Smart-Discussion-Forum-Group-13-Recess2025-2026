<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\TopicView;

class TopicController extends Controller
{
    // GET /topics — list all topics for the Discussions screen
    public function index(Request $request): JsonResponse
    {
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

        $topics->each(function ($topic) use ($request) {
            if ($topic->group) {
                $topic->group->is_member = $topic->group->members()
                    ->where('user_id', $request->user()->id)
                    ->exists();
            }
        });

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

        $topic = Topic::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $topic->load('user:id,name');

        return response()->json($topic, 201);
    }

    // GET /api/topics/{topic} — view a single topic
    public function show(Topic $topic): JsonResponse
    {
        $topic->load('user:id,name');

        return response()->json($topic);
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
}