<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplierOrderErrorEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $errorMessage;
    public $adminId;
    public function __construct($errorMessage, $adminId)
    {
        $this->errorMessage = $errorMessage;
        $this->adminId = $adminId;
    }

    public function broadcastOn(): array
    {
        // Adminii vor asculta pe canalul privat 'admin-channel'
        return [
            new PrivateChannel('admin-channel.' . $this->adminId),
        ];
    }

    public function broadcastAs()
    {
        return 'SupplierOrderError';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->errorMessage,
        ];
    }
}
