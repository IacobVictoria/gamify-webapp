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

class OpponentJoined implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sessionId;
    public $opponentName;

    /**
     * Create a new event instance.
     */
    public function __construct($sessionId, $opponentName)
    {
        $this->sessionId = $sessionId;
        $this->opponentName = $opponentName;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('hangman-session.' . $this->sessionId);
    }
    public function broadcastAs()
    {
        return 'OpponentJoined';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'opponentName' => $this->opponentName,
        ];
    }
}
