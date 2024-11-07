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

class ObtainBadge implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $badge;
    public function __construct($user, $badge)
    {
        $this->user = $user;
        $this->badge = $badge;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('obtain_badge.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'ObtainBadge';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Felicitări, ' . $this->user->name . '! Ai obținut badge-ul: ' . $this->badge->name . '+' . $this->badge->score,

        ];
    }
}
