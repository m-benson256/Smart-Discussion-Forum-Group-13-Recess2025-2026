<?php

namespace App\Events;
use App\model\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageText;
    public $senderName;

    public function __construct($senderName, $messageText)
    {
        $this->senderName = $senderName;
        $this->messageText = $messageText;
    }

    public function broadcastWith(): array
    {
        return [
            'sender' => $this->senderName,
            'message' => $this->messageText,
            'time' => now()->toTimeString(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('forum-notifications'),
        ];
    }
}