<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    // POST /quizzes/{quiz}/questions — add a question (the "Add Questions" tab)
    public function store(Request $request, Quiz $quiz): JsonResponse
    {
        if ($quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:mcq,tf,sa',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'options' => 'required_if:type,mcq|array',
            'options.*.option_key' => 'required_with:options|string|max:1',
            'options.*.option_text' => 'required_with:options|string',
        ]);

        $question = $quiz->questions()->create([
            'type' => $validated['type'],
            'prompt' => $validated['prompt'],
            'correct_answer' => $validated['correct_answer'],
            'order' => $quiz->questions()->count(),
        ]);

        if ($validated['type'] === 'mcq') {
            foreach ($validated['options'] as $option) {
                $question->options()->create($option);
            }
        }

        $question->load('options');

        return response()->json($question, 201);
    }

    // PUT /questions/{question} — edit a question
    public function update(Request $request, QuizQuestion $question): JsonResponse
    {
        if ($question->quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:mcq,tf,sa',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'options' => 'required_if:type,mcq|array',
            'options.*.option_key' => 'required_with:options|string|max:1',
            'options.*.option_text' => 'required_with:options|string',
        ]);

        $question->update([
            'type' => $validated['type'],
            'prompt' => $validated['prompt'],
            'correct_answer' => $validated['correct_answer'],
        ]);

        if ($validated['type'] === 'mcq') {
            $question->options()->delete();
            foreach ($validated['options'] as $option) {
                $question->options()->create($option);
            }
        } else {
            $question->options()->delete();
        }

        $question->load('options');

        return response()->json($question);
    }

    // DELETE /questions/{question} — remove a question
    public function destroy(Request $request, QuizQuestion $question): JsonResponse
    {
        if ($question->quiz->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $question->delete();

        return response()->json(['message' => 'Question deleted']);
    }
}
