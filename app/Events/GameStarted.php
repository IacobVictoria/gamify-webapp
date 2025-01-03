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

class GameStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;

    /**
     * Creează o nouă instanță a evenimentului.
     */
    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Canalul pe care va fi transmis evenimentul.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('hangman-session.' . $this->sessionId);
    }

    /**
     * Nume pentru eveniment.
     */
    public function broadcastAs()
    {
        return 'GameStarted';
    }
}
