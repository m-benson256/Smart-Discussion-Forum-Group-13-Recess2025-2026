<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionOption;
use App\Models\Question;

class QuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();

        if ($questions->isEmpty()) {
            $this->command->error('❌ No questions found!');
            return;
        }

        $options = [];

        // Question 1: What does PHP stand for?
        $options[] = [
            'question_id' => $questions[0]->id,
            'option_text' => 'PHP: Hypertext Preprocessor',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[0]->id,
            'option_text' => 'Personal Home Page',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[0]->id,
            'option_text' => 'Private Home Page',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[0]->id,
            'option_text' => 'Preprocessor Hypertext',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Question 2: Which symbol starts a PHP variable?
        $options[] = [
            'question_id' => $questions[1]->id,
            'option_text' => '$',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[1]->id,
            'option_text' => '#',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[1]->id,
            'option_text' => '&',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[1]->id,
            'option_text' => '@',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Question 3: True/False question (only 2 options)
        $options[] = [
            'question_id' => $questions[2]->id,
            'option_text' => 'True',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[2]->id,
            'option_text' => 'False',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Question 4: What is the output?
        $options[] = [
            'question_id' => $questions[3]->id,
            'option_text' => 'HelloWorld',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[3]->id,
            'option_text' => 'Hello World',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[3]->id,
            'option_text' => 'Hello.World',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $options[] = [
            'question_id' => $questions[3]->id,
            'option_text' => 'Hello+World',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        foreach ($options as $option) {
            QuestionOption::create($option);
        }

        $this->command->info('✅ Question Options seeded successfully!');
    }
}