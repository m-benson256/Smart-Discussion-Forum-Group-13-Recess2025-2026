<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\Student;

class QuizAttemptSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::first();
        $student = Student::first();

        if (!$quiz || !$student) {
            $this->command->error('❌ No quiz or student found!');
            return;
        }

        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'start_time' => now(),
            'end_time' => null,
            'score' => 0,
            'status' => 'in_progress',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Quiz Attempt seeded successfully!');
    }
}