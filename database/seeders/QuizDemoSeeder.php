<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuizDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $lecturer = User::updateOrCreate(
                ['email' => 'lecturer@example.ed'],
                [
                    'name' => 'Dr. Ada Lecturer',
                    'password' => Hash::make('password'),
                    'role' => 'lecturer',
                    'status' => 'active',
                ]
            );

            $student = User::updateOrCreate(
                ['email' => 'student@example.ed'],
                [
                    'name' => 'Sam Student',
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => 'active',
                ]
            );

            $webCategory = Category::updateOrCreate(
                ['CategoryName' => 'Web Development'],
                []
            );

            $csCategory = Category::updateOrCreate(
                ['CategoryName' => 'Computer Science'],
                []
            );

            $publishedQuiz = Quiz::updateOrCreate(
                ['title' => 'Laravel Routing Fundamentals'],
                [
                    'created_by' => $lecturer->id,
                    'category_id' => $webCategory->CategoryID,
                    'description' => 'A starter quiz covering routes, controllers, and request handling.',
                    'start_time' => now()->addDay(),
                    'duration_minutes' => 30,
                    'total_marks' => 20,
                    'passing_score' => 70,
                    'shuffle_questions' => true,
                    'status' => 'published',
                ]
            );

            $draftQuiz = Quiz::updateOrCreate(
                ['title' => 'PHP Basics Practice'],
                [
                    'created_by' => $lecturer->id,
                    'category_id' => $csCategory->CategoryID,
                    'description' => 'Draft quiz for testing the quiz authoring flow.',
                    'start_time' => now()->addDays(2),
                    'duration_minutes' => 25,
                    'total_marks' => 15,
                    'passing_score' => 60,
                    'shuffle_questions' => false,
                    'status' => 'draft',
                ]
            );

            $this->seedQuestions($publishedQuiz, [
                [
                    'order' => 1,
                    'type' => 'mcq',
                    'prompt' => 'Which HTTP verb is typically used to create a new resource?',
                    'correct_answer' => 'POST',
                    'options' => [
                        ['option_key' => 'A', 'option_text' => 'GET'],
                        ['option_key' => 'B', 'option_text' => 'POST'],
                        ['option_key' => 'C', 'option_text' => 'PUT'],
                        ['option_key' => 'D', 'option_text' => 'DELETE'],
                    ],
                ],
                [
                    'order' => 2,
                    'type' => 'tf',
                    'prompt' => 'A Laravel controller can return a JSON response.',
                    'correct_answer' => 'True',
                    'options' => [],
                ],
                [
                    'order' => 3,
                    'type' => 'sa',
                    'prompt' => 'What helper is commonly used to generate a route URL in Laravel?',
                    'correct_answer' => 'route',
                    'options' => [],
                ],
            ]);

            $this->seedQuestions($draftQuiz, [
                [
                    'order' => 1,
                    'type' => 'mcq',
                    'prompt' => 'Which symbol is used to start a PHP variable?',
                    'correct_answer' => '$',
                    'options' => [
                        ['option_key' => 'A', 'option_text' => '#'],
                        ['option_key' => 'B', 'option_text' => '$'],
                        ['option_key' => 'C', 'option_text' => '@'],
                        ['option_key' => 'D', 'option_text' => '&'],
                    ],
                ],
                [
                    'order' => 2,
                    'type' => 'sa',
                    'prompt' => 'Name the keyword used to define a function in PHP.',
                    'correct_answer' => 'function',
                    'options' => [],
                ],
            ]);

            $attempt = QuizAttempt::updateOrCreate(
                ['quiz_id' => $publishedQuiz->id, 'user_id' => $student->id],
                [
                    'score' => 18,
                    'started_at' => now()->subDay(),
                    'submitted_at' => now()->subHours(12),
                ]
            );

            $questionMap = $publishedQuiz->questions()->orderBy('order')->get()->keyBy('order');

            QuizAttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionMap[1]->id],
                ['selected_answer' => 'POST', 'is_correct' => true]
            );

            QuizAttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionMap[2]->id],
                ['selected_answer' => 'True', 'is_correct' => true]
            );

            QuizAttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionMap[3]->id],
                ['selected_answer' => 'route', 'is_correct' => true]
            );
        });
    }

    /**
     * @param  array<int, array{order:int,type:string,prompt:string,correct_answer:string,options:array<int, array{option_key:string, option_text:string}>}>  $questions
     */
    private function seedQuestions(Quiz $quiz, array $questions): void
    {
        foreach ($questions as $questionData) {
            $question = QuizQuestion::updateOrCreate(
                [
                    'quiz_id' => $quiz->id,
                    'order' => $questionData['order'],
                ],
                [
                    'type' => $questionData['type'],
                    'prompt' => $questionData['prompt'],
                    'correct_answer' => $questionData['correct_answer'],
                ]
            );

            if ($questionData['type'] !== 'mcq') {
                continue;
            }

            foreach ($questionData['options'] as $optionData) {
                QuizOption::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'option_key' => $optionData['option_key'],
                    ],
                    [
                        'option_text' => $optionData['option_text'],
                    ]
                );
            }
        }
    }
}
