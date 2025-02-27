<?php

namespace App\Events;

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

class ObtainBadge implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $badge, $notificationService;
    public function __construct($user, $badge, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->badge = $badge;
        $this->notificationService = $notificationService;

        $this->makeNotification();

    }

    public function makeNotification()
    {
        $message = '';
        $message = 'Felicitări, ' . $this->user->name . '! Ai obținut badge-ul: ' . $this->badge->name . '+' . $this->badge->score;

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'ObtainBadge'
        ]);

        $notification->save();

        $this->notificationService->updateNotifications($this->user);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('obtain_badge.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'ObtainBadge';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Felicitări, ' . $this->user->name . '! Ai obținut badge-ul: ' . $this->badge->name . '+' . $this->badge->score,

        ];
    }
}
