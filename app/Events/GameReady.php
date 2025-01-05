<?php

namespace App\Events;

use App\Models\HangmanSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameReady implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;

    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('hangman-session.' . $this->sessionId);
    }
    public function broadcastAs()
    {
        return 'GameReady';
    }

    public function broadcastWith()
    {
        $session = HangmanSession::find($this->sessionId);

        return [
            'sessionId' => $this->sessionId,
            'wordForCreator' => $session->word_for_creator,
            'hintForCreator' => $session->hint_for_creator,
            'wordForOpponent' => $session->word_for_opponent,
            'hintForOpponent' => $session->hint_for_opponent,
        ];
    }

}
