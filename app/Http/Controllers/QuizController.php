<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // GET /lecturer/quizzes — list quizzes created by the logged-in lecturer
    public function index(Request $request): JsonResponse
    {
        $quizzes = Quiz::where('created_by', $request->user()->id)
            ->withCount('questions')
            ->latest()
            ->get();

        return response()->json($quizzes);
    }

    // POST /quizzes — create a new draft quiz (the "Configure Quiz" tab)
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'lecturer') {
            return response()->json(['message' => 'Only lecturers can create quizzes'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'start_time' => 'nullable|date',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'shuffle_questions' => 'boolean',
        ]);

        $quiz = Quiz::create([
            ...$validated,
            'created_by' => $request->user()->id,
            'status' => 'draft',
        ]);

        return response()->json($quiz, 201);
    }

    // PUT /quizzes/{quiz} — update quiz config (re-saving the Configure tab)
    public function update(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'start_time' => 'nullable|date',
            'duration_minutes' => 'sometimes|integer|min:1',
            'total_marks' => 'sometimes|integer|min:1',
            'passing_score' => 'sometimes|integer|min:0|max:100',
            'shuffle_questions' => 'boolean',
        ]);

        $quiz->update($validated);

        return response()->json($quiz);
    }

    // GET /quizzes/{quiz} — full quiz with questions+options (for editing or review)
    public function show(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz->load('questions.options');

        return response()->json($quiz);
    }
    // GET /lecturer/quizzes/{quiz}/edit — render the quiz builder pre-filled for editing
public function edit(Request $request, Quiz $quiz): \Illuminate\View\View
{
    abort_if($quiz->created_by !== $request->user()->id, 403);

    $quiz->load('questions.options');

    return view('lecturer.quizzes.create', [
        'quiz' => $quiz,
        'categories' => \App\Models\Category::orderBy('CategoryName')->get(),
    ]);
}

    // POST /quizzes/{quiz}/publish — the "Finish & Save" button
    public function publish(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Guard: already published — return current state, don't re-process
        if ($quiz->status === 'published') {
            return response()->json($quiz);
        }

        if ($quiz->questions()->count() === 0) {
            return response()->json(['message' => 'Cannot publish a quiz with no questions'], 422);
        }

        $quiz->update(['status' => 'published']);

        return response()->json($quiz->fresh());
    }
}
