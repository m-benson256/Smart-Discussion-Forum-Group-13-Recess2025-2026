<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentAnswer;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\QuestionOption;

class StudentAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $attempt = QuizAttempt::first();
        $questions = Question::all();

        if (!$attempt || $questions->isEmpty()) {
            $this->command->error('❌ No attempt or questions found!');
            return;
        }

        foreach ($questions as $question) {
            // Get correct option for each question
            $correctOption = QuestionOption::where('question_id', $question->id)
                ->where('is_correct', true)
                ->first();

            if ($correctOption) {
                StudentAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $correctOption->id,
                    'answer_text' => null,
                    'is_correct' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Student Answers seeded successfully!');
    }
}