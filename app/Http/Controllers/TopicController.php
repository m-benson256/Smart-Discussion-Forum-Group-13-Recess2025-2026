<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    // GET /api/topics — list all topics for the Discussions screen
    public function index(): JsonResponse
    {
        $topics = Topic::with('user:id,name,role')
            ->latest()
            ->get();

        return response()->json($topics);
    }

    // POST /api/topics — create a new topic
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|integer',
            'group_id' => 'nullable|integer',
        ]);

        $topic = Topic::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $topic->load('user:id,name,role');

        return response()->json($topic, 201);
    }

    // GET /api/topics/{topic} — view a single topic
    public function show(Topic $topic): JsonResponse
    {
        $topic->load('user:id,name,role');

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