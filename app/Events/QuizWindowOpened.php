<?php

namespace App\Events;

use App\Models\Quiz;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class QuizWindowOpened implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public Quiz $quiz) {}

    public function broadcastOn(): array
    {
        // Broadcast to a channel scoped by category so only the right students react
        return [new Channel('quiz-category.' . $this->quiz->category_id)];
    }

    public function broadcastAs(): string
    {
        return 'quiz.window.opened';
    }

    public function broadcastWith(): array
    {
        return [
            'quiz_id' => $this->quiz->id,
            'title' => $this->quiz->title,
            'duration_minutes' => $this->quiz->duration_minutes,
            'start_time' => $this->quiz->start_time->toIso8601String(),
        ];
    }
}