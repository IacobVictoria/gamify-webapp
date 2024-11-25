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

class FriendRequestAccepted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $senderFriendRequest;
    protected $receiverFriendRequest, $notificationService;
    public function __construct($senderFriendRequest, $receiverFriendRequest, NotificationService $notificationService)
    {
        $this->senderFriendRequest = $senderFriendRequest;
        $this->receiverFriendRequest = $receiverFriendRequest;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }

    public function makeNotification()
    {
        $message = '';
        $message = $this->senderFriendRequest->name . ' ti-a acceptat cererea de prietenie !';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->senderFriendRequest->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'FriendRequest'
        ]);

        $notification->save();

        $this->notificationService->updateNotifications($this->senderFriendRequest);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->senderFriendRequest->id),
        ];
    }

    public function broadcastAs()
    {
        return 'FriendRequestAccepted';
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->senderFriendRequest->name . ' ti-a acceptat cererea de prietenie !',
        ];
    }
}
