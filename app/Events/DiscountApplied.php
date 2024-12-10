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

class DiscountApplied implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discountDetails, $notificationService, $userId;
    public function __construct($discountDetails, NotificationService $notificationService, $userId)
    {
        $this->discountDetails = $discountDetails;
        $this->userId = $userId;
        $this->notificationService = $notificationService;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';

        $message = $this->discountDetails;
        $users = User::all();

        foreach ($users as $user) {
            Notification::create([
                'id' => Uuid::uuid(),
                'user_id' => $user->id,
                'type' => 'DiscountApplied',
                'message' => $message,
                'data' => json_encode($this->discountDetails),
                'is_read' => false,
            ]);
            $this->notificationService->updateNotifications($user);
        }


    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user' . $this->userId),
        ];
    }
    public function broadcastAs(): string
    {
        return 'DiscountApplied';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->discountDetails,
        ];
    }
}
