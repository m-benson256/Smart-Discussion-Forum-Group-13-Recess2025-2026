<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Step 1: Seed users/people first
            LecturerSeeder::class,
            StudentSeeder::class,
            
            // Step 2: Seed categories
            CategorySeeder::class,
            
            // Step 3: Seed quizzes (needs lecturers and categories)
            QuizSeeder::class,
            
            // Step 4: Seed questions (needs quizzes)
            QuestionSeeder::class,
            
            // Step 5: Seed question options (needs questions)
            QuestionOptionSeeder::class,
            
            // Step 6: Seed quiz attempts (needs quizzes and students)
            QuizAttemptSeeder::class,
            
            // Step 7: Seed student answers (needs attempts, questions, options)
            StudentAnswerSeeder::class,

            // 8. Quiz Submission Logs (needs attempts)
             QuizSubmissionLogSeeder::class, // Add this

        ]);
    }
}