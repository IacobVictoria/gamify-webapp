<?php

namespace App\Events;

use App\Models\ClientOrder;
use App\Models\Notification;
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

class OrderDeliveredEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $order;
    public $notificationService;

    public function __construct($user, ClientOrder $order, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->order = $order;
        $this->notificationService = $notificationService;

        $this->makeNotification();

    }
    public function makeNotification()
    {
        $message = "Comanda ta (#{$this->order->id}) a fost livrată cu succes! 🎉";

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'OrderDelivered'
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
        return 'OrderDelivered';
    }

    public function broadcastWith()
    {
        return [
            'message' => "Comanda ta (#{$this->order->id}) a fost livrată cu succes! 🎉",
            'order_id' => $this->order->id,
             'order_status'=>'Delivered'
        ];
    }
}
