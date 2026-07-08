<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    // GET /student/quizzes — list published quizzes, split by attempted/not
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $quizzes = Quiz::where('status', 'published')
            ->withCount('questions')
            ->with(['attempts' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->get();

        $quizzes = $quizzes->map(function ($quiz) {
            $myAttempt = $quiz->attempts->first(); // will be null if not attempted

            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'duration_minutes' => $quiz->duration_minutes,
                'start_time' => $quiz->start_time,
                'total_marks' => $quiz->total_marks,
                'questions_count' => $quiz->questions_count,
                'status' => $myAttempt && $myAttempt->submitted_at ? 'submitted' : 'incoming',
                'score' => $myAttempt->score ?? null,
                'submitted_at' => $myAttempt->submitted_at ?? null,
            ];
        });

        return response()->json($quizzes);
    }

    // POST /quizzes/{quiz}/start — begin (or resume) an attempt, return questions without answers
    public function start(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->status !== 'published') {
            return response()->json(['message' => 'Quiz is not available'], 403);
        }

        $attempt = QuizAttempt::firstOrCreate(
            ['quiz_id' => $quiz->id, 'user_id' => $request->user()->id],
            ['started_at' => now()]
        );

        if ($attempt->submitted_at) {
            return response()->json(['message' => 'You have already submitted this quiz'], 409);
        }

        $quiz->load(['questions' => function ($query) {
            $query->select('id', 'quiz_id', 'type', 'prompt', 'order')
                ->orderBy('order');
        }, 'questions.options:id,question_id,option_key,option_text']);

        return response()->json([
            'attempt_id' => $attempt->id,
            'started_at' => $attempt->started_at,
            'quiz' => $quiz,

        ]);
    }

    // POST /attempts/{attempt}/submit — grade and finalize
    public function submit(Request $request, QuizAttempt $attempt): JsonResponse
{
    if ($attempt->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($attempt->submitted_at) {
        return response()->json(['message' => 'Already submitted'], 409);
    }

    // --- NEW: server-side deadline enforcement ---
    $deadline = $attempt->started_at->copy()->addMinutes($attempt->quiz->duration_minutes);
    $graceSeconds = 10; // small buffer for network/request latency

    if (now()->greaterThan($deadline->addSeconds($graceSeconds))) {
        $deadline = $attempt->started_at->copy()->addMinutes($attempt->quiz->duration_minutes);
$graceSeconds = 10; // small buffer for network/request latency

if (now()->greaterThan($deadline->addSeconds($graceSeconds))) {
    return response()->json([
        'message' => 'Time limit exceeded. This attempt can no longer be submitted.',
    ], 422);
}
    }
    // --- end new block ---

    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*.question_id' => 'required|integer|exists:quiz_questions,id',
        'answers.*.selected_answer' => 'nullable|string',
    ]);

    $quiz = $attempt->quiz;
    $questions = $quiz->questions()->get()->keyBy('id');
    $correctCount = 0;

    foreach ($validated['answers'] as $answer) {
        $question = $questions->get($answer['question_id']);
        if (! $question) {
            continue;
        }

        $selected = trim($answer['selected_answer'] ?? '');
        $isCorrect = strcasecmp($selected, $question->correct_answer) === 0;

        if ($isCorrect) {
            $correctCount++;
        }

        $attempt->answers()->create([
            'question_id' => $question->id,
            'selected_answer' => $selected,
            'is_correct' => $isCorrect,
        ]);
    }

    $totalQuestions = $questions->count();
    $score = $totalQuestions > 0
        ? (int) round(($correctCount / $totalQuestions) * $quiz->total_marks)
        : 0;

    $attempt->update([
        'score' => $score,
        'submitted_at' => now(),
    ]);

    return response()->json([
        'score' => $score,
        'total_marks' => $quiz->total_marks,
        'correct_count' => $correctCount,
        'total_questions' => $totalQuestions,
        'submitted_at' => $attempt->submitted_at?->toIso8601String(),
    ]);
}
}