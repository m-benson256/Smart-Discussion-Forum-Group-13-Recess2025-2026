<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // GET /topics/{topic}/messages — list all replies in a topic
public function index(Request $request, Topic $topic): JsonResponse
{
    $userId = $request->user()->id;
    $topic->load('group:id,visibility,created_by');

    if ($topic->group && $topic->group->visibility === 'private' && $request->user()->role !== 'lecturer') {
        $isMember = $topic->group->created_by === $userId
            || $topic->group->members()->where('user_id', $userId)->exists();

        if (!$isMember) {
            return response()->json(['message' => 'You do not have access to this discussion'], 403);
        }
    }

    $messages = $topic->messages()
        ->with(['user:id,name,avatar_path', 'reactions']) 
        ->withCount(['flaggedBy', 'likedBy'])
        ->oldest()
        ->get();

    $messages->each(function ($message) use ($userId) {
        $message->liked_by_me = $message->likedBy->contains('id', $userId);
        $message->flagged_by_me = $message->flaggedBy->contains('id', $userId);
         
         if ($message->user) {
            $message->user->avatar_url = $message->user->avatar_path
                ? Storage::disk('public')->url($message->user->avatar_path)
                : null;
        }

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

// GET /topics/{topic}/export-pdf — download the thread as a PDF
public function exportPdf(Request $request, Topic $topic)
{
    $userId = $request->user()->id;
    $topic->load('group:id,visibility,created_by', 'user:id,name');

    if ($topic->group && $topic->group->visibility === 'private' && $request->user()->role !== 'lecturer') {
        $isMember = $topic->group->created_by === $userId
            || $topic->group->members()->where('user_id', $userId)->exists();

        if (!$isMember) {
            abort(403, 'You do not have access to this discussion');
        }
    }

    $messages = $topic->messages()
        ->with('user:id,name')
        ->oldest()
        ->get();

    $pdf = Pdf::loadView('exports.topic-thread', [
        'topic' => $topic,
        'messages' => $messages,
        'exportedBy' => $request->user()->name,
    ])->setPaper('a4');

    $safeTitle = Str::slug($topic->title) ?: 'topic';

    return $pdf->download("discussion-{$safeTitle}-{$topic->id}.pdf");
}
}
