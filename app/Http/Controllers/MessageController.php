<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    // GET /topics/{topic}/messages — list all replies in a topic
    public function index(Topic $topic): JsonResponse
    {
        $messages = $topic->messages()
            ->with('user:id,name')
            ->withCount('flaggedBy')
            ->oldest()
            ->get();

        return response()->json($messages);
    }

    // POST /topics/{topic}/messages — post a reply
    public function store(Request $request, Topic $topic): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $message = $topic->messages()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $message->load('user:id,name');

        return response()->json($message, 201);
    }

    // POST /messages/{message}/flag — toggle flag for the current user
    public function toggleFlag(Request $request, Message $message): JsonResponse
    {
        $userId = $request->user()->id;

        $existingFlag = $message->flaggedBy()
            ->where('user_id', $userId)
            ->exists();

        if ($existingFlag) {
            $message->flaggedBy()->detach($userId);
        } else {
            $message->flaggedBy()->attach($userId);
        }

        $flagCount = $message->flaggedBy()->count();

        if ($flagCount > 2) {
            $message->delete(); // soft delete — hides it automatically
        }

        return response()->json([
            'flagged' => !$existingFlag,
            'flag_count' => $flagCount,
            'hidden' => $flagCount > 2,
        ]);
    }
}