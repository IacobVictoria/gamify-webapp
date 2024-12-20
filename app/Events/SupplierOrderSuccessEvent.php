<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\SupplierOrder;
use Faker\Provider\Uuid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplierOrderSuccessEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order, $adminId;

    public function __construct(SupplierOrder $order, $adminId)
    {
        $this->order = $order;
        $this->adminId = $adminId;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';
        $message = 'Comanda cu nr. ' . $this->order->id . ' din data de ' . $this->order->order_date . ' a fost data cu success!';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->adminId,
            'message' => $message,
            'is_read' => false,
            'type' => 'SupplierOrderSuccess'
        ]);

        $notification->save();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-channel.' . $this->adminId),
        ];
    }
    public function broadcastAs()
    {
        return 'SupplierOrderSuccess';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Comanda cu nr. ' . $this->order->id . ' din data de ' . $this->order->order_date . ' a fost data cu success!',
        ];
    }
}
