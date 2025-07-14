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

class UserScoreUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $score, $message, $notificationService;
    public function __construct(User $user, $score, $message = null, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->score = $score;
        $this->message = $message ?? 'Scorul a fost actualizat.';
        $this->notificationService = $notificationService;

        $this->makeNotification();

    }

    public function makeNotification()
    {
        $message = '';
        $message = "Felicitări! Ai câștigat $this->score puncte!";

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'UserScoreUpdated'
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

    public function broadcastWith()
    {
        return [
            'message' => "$this->message Felicitări! Ai câștigat $this->score puncte!",
        ];
    }

    public function broadcastAs()
    {
        return 'UserScoreUpdatedEvent';
    }
}
