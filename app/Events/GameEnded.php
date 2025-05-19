<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $scores;

    public function __construct($sessionId, $scores)
    {
        $this->sessionId = $sessionId;
        $this->scores = $scores;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("hangman-session.{$this->sessionId}");
    }

    public function broadcastAs()
    {
        return 'GameEnded';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Jocul s-a terminat!',
            'scores' => $this->scores
        ];
    }
}
