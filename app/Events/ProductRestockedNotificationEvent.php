<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\Product;
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

class ProductRestockedNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $user;
    public $notificationService;
    public function __construct(Product $product, User $user, NotificationService $notificationService)
    {
        $this->product = $product;
        $this->user = $user;
        $this->notificationService = $notificationService;
        $this->makeNotification();
    }

    public function makeNotification()
    {
        $message = '';

        $message = 'Produsul tau preferat ' . $this->product->name . ' a intrat in stock!';

        if ($this->user->hasRole('User')) {
            $notification = Notification::create([
                'id' => Uuid::uuid(),
                'user_id' => $this->user->id,
                'type' => 'RestockProduct',
                'message' => $message,
                'is_read' => false,
            ]);
            $notification->save();
            $this->notificationService->updateNotifications($this->user);
        }

    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user_restockProduct.' . $this->user->id),
        ];
    }
    public function broadcastAs()
    {
        return 'ProductRestockedNotification';
    }
    public function broadcastWith()
    {
        return [
            'message' => 'Produsul tau preferat ' . $this->product->name . ' a intrat in stock!',
        ];
    }
}
