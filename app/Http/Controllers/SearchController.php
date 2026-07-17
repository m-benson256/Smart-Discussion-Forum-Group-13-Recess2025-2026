<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // GET /lecturer/search?q=... — search quizzes, students, and reports
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));
        $lecturerId = $request->user()->id;

        if ($query === '') {
            return response()->json(['quizzes' => [], 'students' => [], 'reports' => []]);
        }

        $quizzes = Quiz::where('created_by', $lecturerId)
            ->where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'status', 'duration_minutes')
            ->limit(10)
            ->get();

        $students = User::where('role', 'student')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        $reports = QuizAttempt::whereHas('quiz', function ($q) use ($lecturerId) {
                $q->where('created_by', $lecturerId);
            })
            ->whereNotNull('submitted_at')
            ->where(function ($q) use ($query) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('quiz', fn($qz) => $qz->where('title', 'like', "%{$query}%"));
            })
            ->with(['user:id,name', 'quiz:id,title,total_marks'])
            ->limit(10)
            ->get()
            ->map(fn($attempt) => [
                'student_name' => $attempt->user->name ?? 'Unknown',
                'quiz_title' => $attempt->quiz->title ?? 'Unknown',
                'score' => $attempt->score,
                'total_marks' => $attempt->quiz->total_marks ?? null,
            ]);

        return response()->json([
            'quizzes' => $quizzes,
            'students' => $students,
            'reports' => $reports,
        ]);
    }

   // GET /student/search?q=... — search groups, topics, and quizzes visible to this student
public function studentSearch(Request $request): JsonResponse
{
    $query = trim($request->query('q', ''));
    $userId = $request->user()->id;

    if ($query === '') {
        return response()->json(['groups' => [], 'topics' => [], 'quizzes' => []]);
    }

    $visibleToStudent = function ($g) use ($userId) {
        $g->where('visibility', 'public')
          ->orWhere('created_by', $userId)
          ->orWhereHas('members', fn ($m) => $m->where('user_id', $userId));
    };

    $groups = \App\Models\Group::where($visibleToStudent)
        ->where(function ($g) use ($query) {
            $g->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->select('id', 'name', 'description', 'visibility')
        ->limit(10)
        ->get();

    $topics = \App\Models\Topic::where(function ($t) use ($query) {
            $t->where('title', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%");
        })
        ->where(function ($t) use ($visibleToStudent) {
            $t->whereNull('group_id')
              ->orWhereHas('group', $visibleToStudent);
        })
        ->select('id', 'title', 'group_id')
        ->limit(10)
        ->get();

    $quizzes = Quiz::where('status', 'published')
        ->where('title', 'like', "%{$query}%")
        ->select('id', 'title', 'duration_minutes', 'total_marks')
        ->limit(10)
        ->get();

    return response()->json([
        'groups' => $groups,
        'topics' => $topics,
        'quizzes' => $quizzes,
    ]);
}

}