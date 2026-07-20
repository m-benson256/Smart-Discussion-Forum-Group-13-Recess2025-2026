<?php
namespace App\Console\Commands;

use App\Events\QuizWindowOpened;
use App\Models\Quiz;
use Illuminate\Console\Command;

class DispatchQuizStartEvents extends Command
{
    protected $signature = 'quizzes:dispatch-start-events';
    protected $description = 'Broadcast QuizWindowOpened for any quiz whose start_time has just arrived';

    public function handle(): void
    {
        $quizzes = Quiz::where('status', 'published')
            ->whereNull('broadcasted_at')
            ->whereNotNull('start_time')
            ->where('start_time', '<=', now())
            ->get();

        foreach ($quizzes as $quiz) {
            broadcast(new QuizWindowOpened($quiz));
            $quiz->update(['broadcasted_at' => now()]);
            $this->info("Broadcasted quiz #{$quiz->id}: {$quiz->title}");
        }
    }
}