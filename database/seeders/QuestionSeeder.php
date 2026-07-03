<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Quiz;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::first();

        if (!$quiz) {
            $this->command->error('❌ No quiz found!');
            return;
        }

        $questions = [
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'What does PHP stand for?',
                'question_type' => 'multiple_choice',
                'points' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Which symbol is used to start a PHP variable?',
                'question_type' => 'multiple_choice',
                'points' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'PHP is a client-side scripting language. True or False?',
                'question_type' => 'true_false',
                'points' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'What is the output of echo "Hello" . "World";?',
                'question_type' => 'multiple_choice',
                'points' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }

        $this->command->info('✅ Questions seeded successfully!');
    }
}