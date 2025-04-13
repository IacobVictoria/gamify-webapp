<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRemarkedOnQuizEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $remark, $quiz;
    public $notificationService;


    public function __construct($user, $quiz, $remark, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->remark = $remark;
        $this->quiz = $quiz;
        $this->notificationService = $notificationService;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';
        $message = 'Userul ' . $this->user->name . ' a oferit feedback quiz-ului ' . $this->quiz->title . '. A scris: ' . $this->remark->description;
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin-Gamification');
        })->get();

        foreach ($admins as $admin) {

            Notification::create([
                'id' => Uuid::uuid(),
                'user_id' => $admin->id,
                'message' => $message,
                'is_read' => false,
                'type' => 'UserRemarkedOnQuiz',
            ]);
            $this->notificationService->updateNotifications($admin);
        }

    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-gamification-channel'),
        ];
    }
    public function broadcastAs()
    {
        return 'UserRemarkedOnQuiz';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => 'Userul ' . $this->user->name . ' a oferit feedback quiz-ului ' . $this->quiz->title . '. A scris: ' . $this->remark->description
        ];
    }
}
