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

class LeaderboardTop10Event implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  
    public $user;
    public $position;
    protected $notificationService;

    public function __construct(User $user, int $position, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->position = $position;
        $this->notificationService = $notificationService;

        $this->makeNotification();
    }

    public function makeNotification()
    {
        $message = '🎉 Felicitări! Ai intrat în Top 10 pe leaderboard, poziția #' . $this->position . '!';

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'data' => json_encode([
                'position' => $this->position,
                'user_id' => $this->user->id
            ]),
            'type' => 'LeaderboardEntry'
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
        return 'LeaderboardTop10';
    }

    public function broadcastWith()
    {
        return [
            'message' => '🎉 Felicitări! Ai intrat în Top 10 pe leaderboard, poziția #' . $this->position . '!',
        ];
    }
}
