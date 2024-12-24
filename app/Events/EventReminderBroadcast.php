<?php

namespace App\Events;

use App\Models\Event;
use App\Models\Notification;
use App\Models\Participant;
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

class EventReminderBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;
    public $user, $notificationService;

    public function __construct(Event $event, User $user, NotificationService $notificationService)
    {
        $this->event = $event;
        $this->user = $user;
        $this->notificationService = $notificationService;
        $this->makeNotification();
    }
    public function makeNotification()
    {
        $message = '';

        $message = 'Reminder: Evenimentul "' . $this->event->title . '" incepe in 10 minute.';
        $participants = Participant::where('event_id', $this->event->id)->get();

        foreach ($participants as $participant) {
            Notification::create([
                'id' => Uuid::uuid(),
                'user_id' => $participant->user->id,
                'type' => 'ReminderEvent',
                'message' => $message,
                'is_read' => false,
            ]);
            $this->notificationService->updateNotifications($participant->user);

        }

    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user_reminder.' . $this->user->id),
        ];
    }
    public function broadcastAs()
    {
        return 'EventReminderNotification';
    }

    public function broadcastWith()
    {
        return [

            'message' => 'Reminder: Evenimentul "' . $this->event->title . '" incepe in 10 minute. Scaneaza qr-code ul in pagina de evenimente.',
        ];
    }
}

