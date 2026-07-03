<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\QuizSubmissionLog;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;

class AutoSubmitController extends Controller
{
    /**
     * Auto-submit expired quiz attempts.
     * This should be called by a cron job every minute.
     */
    public function autoSubmitExpiredQuizzes()
    {
        $expiredAttempts = QuizAttempt::where('status', 'in_progress')
            ->with('quiz')
            ->get()
            ->filter(function($attempt) {
                return !$this->isWithinTime($attempt);
            });

        $submittedCount = 0;

        foreach ($expiredAttempts as $attempt) {
            $this->autoSubmitQuiz($attempt);
            $submittedCount++;
        }

        return response()->json([
            'message' => 'Auto-submission completed',
            'submitted' => $submittedCount
        ]);
    }

    /**
     * Auto-submit a quiz attempt.
     */
    private function autoSubmitQuiz($attempt)
    {
        DB::transaction(function() use ($attempt) {
            // Calculate score
            $correctAnswers = StudentAnswer::where('attempt_id', $attempt->id)
                ->where('is_correct', true)
                ->count();

            $totalQuestions = $attempt->quiz->questions->count();
            $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

            $answeredQuestions = StudentAnswer::where('attempt_id', $attempt->id)->count();

            $attempt->update([
                'end_time' => now(),
                'score' => $score,
                'status' => 'submitted',
            ]);

            // Log the auto-submission
            QuizSubmissionLog::create([
                'attempt_id' => $attempt->id,
                'quiz_id' => $attempt->quiz_id,
                'student_id' => $attempt->student_id,
                'submission_type' => 'auto_time_expired',
                'score_before_submission' => $score,
                'answered_questions' => $answeredQuestions,
                'total_questions' => $totalQuestions,
                'submitted_at' => now(),
                'notes' => 'Auto-submitted by cron job due to time expiration',
            ]);
        });
    }

    /**
     * Check if attempt is still within time.
     */
    private function isWithinTime($attempt)
    {
        if ($attempt->status === 'submitted') {
            return false;
        }
        
        $timeElapsed = now()->diffInMinutes($attempt->start_time);
        return $timeElapsed < $attempt->quiz->duration_minutes;
    }
}