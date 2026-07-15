<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    // GET /topics/{topic}/messages — list all replies in a topic
public function index(Request $request, Topic $topic): JsonResponse
{
    $userId = $request->user()->id;

    $messages = $topic->messages()
        ->with(['user:id,name', 'reactions'])
        ->withCount(['flaggedBy', 'likedBy'])
        ->oldest()
        ->get();

    $messages->each(function ($message) use ($userId) {
        $message->liked_by_me = $message->likedBy->contains('id', $userId);
        $message->flagged_by_me = $message->flaggedBy->contains('id', $userId);

        $message->grouped_reactions = $message->reactions
            ->groupBy('emoji')
            ->map(function ($group, $emoji) use ($userId) {
                return [
                    'emoji' => $emoji,
                    'count' => $group->count(),
                    'me' => $group->contains('user_id', $userId),
                ];
            })
            ->values();
    });

    return response()->json($messages->makeHidden(['likedBy', 'flaggedBy', 'reactions']));
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

        // 2. Fire the real-time broadcast alert!
    broadcast(new MessageSent(auth()->user()->name, $request->message_text))->toOthers();

   return response()->json([
        'status' => 'success',
        'message' => $message
    ], 201);
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
        $message->delete();
    }

    return response()->json([
        'flagged' => !$existingFlag,
        'flag_count' => $flagCount,
        'hidden' => $flagCount > 2,
    ]);
}

   public function toggleLike(Request $request, Message $message): JsonResponse
{
    $userId = $request->user()->id;

    $existingLike = $message->likedBy()
        ->where('user_id', $userId)
        ->exists();

    if ($existingLike) {
        $message->likedBy()->detach($userId);
    } else {
        $message->likedBy()->attach($userId);
    }

    return response()->json([
        'liked' => !$existingLike,
        'like_count' => $message->likedBy()->count(),
    ]);
}

public function toggleReaction(Request $request, Message $message): JsonResponse
{
    $validated = $request->validate([
        'emoji' => 'required|string|max:16',
    ]);

    $userId = $request->user()->id;

    $existingReaction = $message->reactions()
        ->where('user_id', $userId)
        ->where('emoji', $validated['emoji'])
        ->first();

    if ($existingReaction) {
        $existingReaction->delete();
    } else {
        $message->reactions()->create([
            'user_id' => $userId,
            'emoji' => $validated['emoji'],
        ]);
    }

    // Group all reactions on this message by emoji, with counts and whether the current user reacted with each
    $grouped = $message->reactions()
        ->selectRaw('emoji, count(*) as count')
        ->groupBy('emoji')
        ->get()
        ->map(function ($row) use ($message, $userId) {
            return [
                'emoji' => $row->emoji,
                'count' => $row->count,
                'me' => $message->reactions()
                    ->where('emoji', $row->emoji)
                    ->where('user_id', $userId)
                    ->exists(),
            ];
        });

    return response()->json(['reactions' => $grouped]);
}
}
