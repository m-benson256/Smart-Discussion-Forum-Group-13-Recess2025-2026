<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\Lecturer;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Get first lecturer and category
        $lecturer = Lecturer::first();
        $category = Category::first();

        if (!$lecturer || !$category) {
            $this->command->error('❌ No lecturer or category found!');
            return;
        }

        $quizzes = [
            [
                'title' => 'PHP Basics Quiz',
                'description' => 'Test your knowledge of PHP fundamentals',
                'lecturer_id' => $lecturer->id,
                'category_id' => $category->id,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'duration_minutes' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laravel Fundamentals Quiz',
                'description' => 'Quiz on Laravel framework basics',
                'lecturer_id' => $lecturer->id,
                'category_id' => $category->id,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(8),
                'duration_minutes' => 45,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($quizzes as $quiz) {
            Quiz::create($quiz);
        }

        $this->command->info('✅ Quizzes seeded successfully!');
    }
}