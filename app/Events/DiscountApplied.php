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

    public $discountDetails, $notificationService, $user;
    public function __construct($discountDetails, NotificationService $notificationService,User $user)
    {
        $this->discountDetails = $discountDetails;
        $this->user = $user;
        $this->notificationService = $notificationService;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';

        $message = $this->discountDetails;
      
        Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'type' => 'DiscountApplied',
            'message' => $message,
            'data' => json_encode($this->discountDetails),
            'is_read' => false,
        ]);
        $this->notificationService->updateNotifications($this->user);
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user_newDiscount.' . $this->user->id),
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
