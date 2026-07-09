<?php

namespace App\Console\Commands;

use App\Models\QuizAttempt;
use Illuminate\Console\Command;

class CloseExpiredQuizAttempts extends Command
{
    protected $signature = 'quizzes:close-expired';
    protected $description = 'Auto-submit quiz attempts whose time limit has passed but were never submitted';

    public function handle(): int
    {
        $attempts = QuizAttempt::whereNull('submitted_at')
            ->whereNotNull('started_at')
            ->with(['quiz', 'answers'])
            ->get()
            ->filter(function (QuizAttempt $attempt) {
                $deadline = $attempt->deadline();
                return $deadline && now()->greaterThan($deadline);
            });

        foreach ($attempts as $attempt) {
            $quiz = $attempt->quiz;
            $totalQuestions = $quiz->questions()->count();

            $correctCount = $attempt->answers()->where('is_correct', true)->count();

            $score = $totalQuestions > 0
                ? (int) round(($correctCount / $totalQuestions) * $quiz->total_marks)
                : 0;

            $attempt->update([
                'score' => $score,
                'submitted_at' => $attempt->deadline(),
            ]);

            $this->info("Closed attempt #{$attempt->id} (quiz #{$quiz->id}, user #{$attempt->user_id}) — score {$score}/{$quiz->total_marks}");
        }

        return self::SUCCESS;
    }
}