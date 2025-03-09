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

class UserEligibleForPromoCodeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $discount, $notificationService;

    public function __construct(User $user, $discount, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->discount = $discount;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';
        $message = '🎉 Ai acumulat suficiente puncte pentru un discount de' . $this->discount . '%!';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'UserEligibleForDiscount'
        ]);

        $notification->save();

        $this->notificationService->updateNotifications($this->user);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }
    public function broadcastAs()
    {
        return 'UserEligibleForDiscount';
    }

    public function broadcastWith()
    {
        return [
            'message' => "🎉 Ai acumulat suficiente puncte pentru un discount de {$this->discount}%!"
        ];
    }
}
