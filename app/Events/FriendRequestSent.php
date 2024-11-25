<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender;
    public $receiverId, $notificationService;
    public function __construct(?User $sender, $receiverId, NotificationService $notificationService)
    {
        $this->sender = $sender;
        $this->receiverId = $receiverId;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }
    public function makeNotification()
    {
        $receiver = User::find($this->receiverId);
        $message = '';
        $message = 'Ai primit un nou friend request de la ' . $this->sender->name . ' !';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->receiverId,
            'message' => $message,
            'data' => json_encode([
                'sender_id' => $this->sender->id,
            ]),
            'is_read' => false,
            'type' => 'FriendRequest'
        ]);

        $notification->save();

        $this->notificationService->updateNotifications($receiver);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('friend-requests.' . $this->receiverId),
        ];
    }
    public function broadcastAs()
    {
        return 'FriendRequestSent';
    }
    public function broadcastWith()
    {
        return [
            'message' => 'Ai primit un nou friend request de la ' . $this->sender->name . ' !',
        ];
    }
}
