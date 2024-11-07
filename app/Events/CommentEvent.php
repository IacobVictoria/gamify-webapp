<?php

namespace App\Events;

use App\Models\Notification;
use Faker\Provider\Uuid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class CommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment, $user, $reviewOwner;

    public function __construct($comment, $user, $reviewOwner)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->reviewOwner = $reviewOwner;
        
        if ($this->user->id !== $this->reviewOwner->id) {
            $this->makeNotification();
        }
    }

    public function makeNotification()
    {
        $message = '';
        $message = 'Userul: ' . $this->user->name . ' a comentat la review-ul tau. Descrierea: ' . $this->comment->description;

        $notification = Notification::create([
            'id' => Uuid::uuid(),
            'user_id' => $this->user->id,
            'message' => $message,
            'is_read' => false,
            'type' => 'CommentEvent'
        ]);
        $notification->save();
    }
    public function broadcastOn(): array
    {
        if ($this->user->id !== $this->reviewOwner->id) {
            return [
                new PrivateChannel('comments.' . $this->reviewOwner->id),
            ];
        }
        return [];

    }

    public function broadcastAs()
    {
        return 'CommentEvent';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Userul: ' . $this->user->name . ' a comentat la review-ul tau.' . ' Descrierea: ' . $this->comment->description,
        ];
    }
}
