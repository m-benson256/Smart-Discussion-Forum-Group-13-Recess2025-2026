<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ParticipationCriteria;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    // GET /lecturer/participation/criteria — get this lecturer's current settings
    public function getCriteria(Request $request): JsonResponse
    {
        $criteria = ParticipationCriteria::firstOrCreate(
            ['lecturer_id' => $request->user()->id],
            ['points_per_message' => 1, 'points_per_reaction_given' => 0, 'max_score' => 100]
        );

        return response()->json($criteria);
    }

    // POST /lecturer/participation/criteria — save updated settings
    public function saveCriteria(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'points_per_message' => 'required|integer|min:0',
            'points_per_reaction_given' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:1',
        ]);

        $criteria = ParticipationCriteria::updateOrCreate(
            ['lecturer_id' => $request->user()->id],
            $validated
        );

        return response()->json($criteria);
    }

    // GET /lecturer/participation/scores — computed scores for every student
    public function scores(Request $request): JsonResponse
    {
        $criteria = ParticipationCriteria::firstOrCreate(
            ['lecturer_id' => $request->user()->id],
            ['points_per_message' => 1, 'points_per_reaction_given' => 0, 'max_score' => 100]
        );

        $students = User::where('role', 'student')
            ->withCount([
                'messages as message_count',
                'reactionsGiven as reactions_given_count',
            ])
            ->get();

        $scored = $students->map(function ($student) use ($criteria) {
    $rawScore = ($student->message_count * $criteria->points_per_message)
        + ($student->reactions_given_count * $criteria->points_per_reaction_given);

    $finalScore = min($rawScore, $criteria->max_score);

    return [
        'student_name' => $student->name,
        'message_count' => $student->message_count,
        'reactions_given_count' => $student->reactions_given_count,
        'score' => $finalScore,
        'max_score' => $criteria->max_score,
    ];
})->sortByDesc('score')->values();

return response()->json($scored);
    }
}