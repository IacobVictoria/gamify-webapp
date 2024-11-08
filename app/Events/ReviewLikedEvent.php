<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\Review;
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

class ReviewLikedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $review, $notificationService;
    public function __construct($user, $review, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->review = $review;
        $this->notificationService = $notificationService;

        $this->makeNotification();

    }
    public function makeNotification()
    {
        $message = '';
        $message = 'Userul ' . $this->user->name . 'ti-a dat like la review-ul: ' . $this->review->description;

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->review->user->id,
            'message' => $message,
            'is_read' => false,
            'data' => json_encode([
                'review_id' => $this->review->id,
                'user_id' => $this->user->id
            ]),
            'type' => 'ReviewLiked'
        ]);

        $notification->save();

        $this->notificationService->updateNotifications($this->review->user);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('review_liked.' . $this->review->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'ReviewLikedEvent';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Userul ' . $this->user->name . 'ti-a dat like la review-ul: ' . $this->review->description,
        ];
    }


}
