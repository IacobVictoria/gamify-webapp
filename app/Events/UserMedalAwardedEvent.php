<?php

namespace App\Events;

use App\Models\Medal;
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
use App\Models\Notification;

class UserMedalAwardedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $medal,$notificationService;

    public function __construct(User $user, Medal $medal,NotificationService $notificationService)
    {
        $this->user = $user;
        $this->medal = $medal;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';
        $message = 'Felicitări! Ai primit medalia de ' . $this->medal->tier . '! 🏅';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'UserMedalAwarded'
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
            new PrivateChannel('user.' . $this->user->id),
        ];
    }
    public function broadcastAs()
    {
        return 'UserMedalAwarded';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Felicitări! Ai primit medalia de ' . $this->medal->tier . '! 🏅',
        ];
    }
}
