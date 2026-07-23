<?php

namespace App\Http\Controllers;

use App\Models\Announcements;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $announcements = Announcements::with('user:id,name', 'quiz:id,title')
        ->where(function ($query) {
            $query->whereNull('recipient_id')
                  ->orWhere('recipient_id', auth()->id());
        })
        ->latest()
        ->get();

    return response()->json($announcements);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($quiz->status !== 'published') {
            return response()->json(['message' => 'You can only announce a published quiz.'], 422);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $announcement = Announcements::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'content' => $validated['content'],
        ]);

        return response()->json($announcement, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcements $announcements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcements $announcements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcements $announcements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcements $announcements)
    {
        //
    }
}
