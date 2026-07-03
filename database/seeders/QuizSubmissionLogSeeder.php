<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuizSubmissionLog;
use App\Models\QuizAttempt;

class QuizSubmissionLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a submitted attempt
        $attempt = QuizAttempt::where('status', 'submitted')->first();

        if ($attempt) {
            QuizSubmissionLog::create([
                'attempt_id' => $attempt->id,
                'quiz_id' => $attempt->quiz_id,
                'student_id' => $attempt->student_id,
                'submission_type' => 'manual',
                'score_before_submission' => $attempt->score,
                'answered_questions' => $attempt->answers()->count(),
                'total_questions' => $attempt->quiz->questions->count(),
                'submitted_at' => $attempt->end_time ?? now(),
                'notes' => 'Manually submitted by student'
            ]);
        }

        $this->command->info('✅ Quiz Submission Logs seeded successfully!');
    }
}