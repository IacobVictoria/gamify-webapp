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

class GameUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sessionId;
    public $turn;
    public $correctLetters;
    public $wrongLetters;
    public $usedLetters;
    public $creatorErrors, $opponentErrors;

    public function __construct($sessionId, $turn, $correctLetters, $wrongLetters, $usedLetters, $creatorErrors, $opponentErrors)
    {
        $this->sessionId = $sessionId;
        $this->turn = $turn;
        $this->correctLetters = $correctLetters;
        $this->wrongLetters = $wrongLetters;
        $this->usedLetters = $usedLetters;
        $this->creatorErrors = $creatorErrors;
        $this->opponentErrors = $opponentErrors;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("hangman-session.{$this->sessionId}");
    }
    public function broadcastAs()
    {
        return 'GameUpdated';
    }
    public function broadcastWith()
    {
        return [
            'turn' => $this->turn,
            'correctLetters' => $this->correctLetters,
            'wrongLetters' => $this->wrongLetters,
            'usedLetters' => $this->usedLetters,
            'creatorErrors' => $this->creatorErrors,
            'opponentErrors' => $this->opponentErrors,
        ];
    }
}
