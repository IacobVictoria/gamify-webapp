<?php

namespace App\Events;

use App\Models\Event;
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
use Illuminate\Support\Facades\Auth;

class NewEventBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event, $notificationService, $user;

    public function __construct(Event $event, NotificationService $notificationService, User $user)
    {
        $this->event = $event;
        $this->notificationService = $notificationService;
        $this->user = $user;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';

        $message = 'Evenimentul ' . $this->event->title . ' este disponibil! Intra pe pagina de Events';

        if ($this->user->hasRole('User')) {
            $notification = Notification::create([
                'id' => Uuid::uuid(),
                'user_id' => $this->user->id,
                'type' => 'NewEvent',
                'message' => $message,
                'is_read' => false,
            ]);
            $notification->save();
            $this->notificationService->updateNotifications($this->user);
        }

    }

    public function broadcastOn()
    {
        return new PrivateChannel('user_newEvent.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'NewEventNotification';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Evenimentul ' . $this->event->title . ' este disponibil! Intra pe pagina de Events',
        ];
    }
}
