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

class OrderFailedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $order;
    public $notificationService;
    public $errorMessage;

    public function __construct($user, ClientOrder $order, NotificationService $notificationService, $errorMessage)
    {
        $this->user = $user;
        $this->order = $order;
        $this->notificationService = $notificationService;
        $this->errorMessage = $errorMessage;

        $this->makeNotification();
    }

    public function makeNotification()
    {
        $message = 'Comanda ta cu ID-ul #' . $this->order->id . ' a eșuat: ' . $this->errorMessage;

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'OrderFailed'
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
        return 'OrderFailed';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Comanda ta cu ID-ul #' . $this->order->id . ' a eșuat: ' . $this->errorMessage,
        ];
    }
}
