<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUnreadUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unreadMessages;
    public $friendId;
    public $user;
    public function __construct($unreadMessages, $friendId, $user)
    {
        $this->unreadMessages = $unreadMessages;
        $this->friendId = $friendId;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user_message.' . $this->friendId),
        ];
    }
    public function broadcastAs()
    {
        return 'MessageUnreadUpdated';
    }
    public function broadcastWith()
    {
        return [
            'unreadCount' => $this->unreadMessages,
            'friendId' => $this->user->id
        ];
    }
}
