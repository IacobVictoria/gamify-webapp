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

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $senderId;
    public $receiverId;

    public function __construct($senderId, $receiverId)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }


    public function broadcastOn()
    {
        return [
            new PrivateChannel('chat_read.' . $this->senderId),
        ];
    }

    /**
     * Numele evenimentului pe care îl va asculta frontend-ul.
     */
    public function broadcastAs()
    {
        return 'MessageRead';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'sender_id' => $this->senderId,
            ],
        ];
    }
}
