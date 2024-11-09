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

class UserMadeLeaderboardEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $notificationService;
    public function __construct(User $user, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';
        $message = 'Felicitări! Ai intrat în topul leaderboard-ului!';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'UserMadeLeaderboard'
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
            new PrivateChannel('leaderboard.'.$this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'UserMadeLeaderboard';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Felicitări! Ai intrat în topul leaderboard-ului!',
        ];
    }
}
