<?php

namespace App\Console\Commands;

use App\Events\EventReminderBroadcast;
use App\Events\NewEventBroadcast;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ManageEventsCommand extends Command
{
    protected $signature = 'app:manage-events-command';
    protected $description = 'Manage events - close past events, notify users about new events and send reminders to participants';
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }


    public function handle()
    {
        $this->info('Running the event management command...');

        // 1. Set events that are past due as closed
        $this->closePastEvents();

        // 2. Emit a notification to all users about a new event
        $this->notifyUsersAboutNewEvent();

        // 3. Emit a notification to participants 10 minutes before the event starts
        $this->sendEventReminder();
    }
    protected function closePastEvents()
    {
        $events = Event::where('start', '<', Carbon::now())->where('status', '!=', 'CLOSED')->get();

        foreach ($events as $event) {
            $event->status = 'CLOSED';
            $event->save();

            // Ștergem cache-ul pentru notificarea evenimentului nou și reminder
            $participants = Participant::where('event_id', $event->id)->get();
            foreach ($participants as $participant) {
                $userCacheKeyNewEvent = 'user_' . $participant->user->id . '_new_event_notification_sent_' . $event->id;
                Cache::forget($userCacheKeyNewEvent);  // Ștergem cache-ul pentru notificarea evenimentului nou

                $userCacheKeyReminder = 'user_' . $participant->user->id . '_event_reminder_' . $event->id;
                Cache::forget($userCacheKeyReminder);  // Ștergem cache-ul pentru reminder
            }

            $this->info("Event '{$event->title}' has been closed and cache has been cleared.");
        }
    }
    protected function notifyUsersAboutNewEvent()
    {
        // Găsim toate evenimentele viitoare
        $events = Event::where('start', '>=', Carbon::now())->orderBy('start')->get();

        foreach ($events as $event) {
            // Verificăm dacă notificarea pentru acest eveniment a fost deja trimisă
            $users = User::all();
            foreach ($users as $user) {
                $userCacheKey = 'user_' . $user->id . '_new_event_notification_sent_' . $event->id;
logger($userCacheKey);
                // Dacă notificarea a fost deja trimisă pentru acest utilizator, o să o sărim
                if (Cache::has($userCacheKey)) {
                    logger('exista');
                    $this->info("Notification for event '{$event->title}' has already been sent to user '{$user->email}'.");
                    continue;
                }

                // Trimitem notificarea doar dacă nu a fost trimisă deja
                broadcast(new NewEventBroadcast($event, $this->notificationService, $user));
                $this->info("Notification sent to user '{$user->email}' about the new event '{$event->title}'.");

                // Salvăm cache-ul pentru utilizatorul respectiv pentru acest eveniment
                Cache::put($userCacheKey, true);  // Setăm cache-ul pentru 24 de ore
            }
        }
    }
  

    protected function sendEventReminder()
    {
        // Find events starting in 10 minutes
        $events = Event::where('start', '<=', Carbon::now()->addMinutes(10))
            ->where('start', '>=', Carbon::now())
            ->where('status', '!=', 'CLOSED')
            ->get();

        foreach ($events as $event) {
            // Get all participants for this event
            $participants = Participant::where('event_id', $event->id)->get();

            foreach ($participants as $participant) {
                $user = $participant->user;

                $userCacheKeyReminder = 'user_' . $user->id . '_event_reminder_' . $event->id;

                // Dacă reminder-ul a fost deja trimis pentru acest utilizator, o să o sărim
                if (Cache::has($userCacheKeyReminder)) {
                    $this->info("Reminder for event '{$event->title}' has already been sent to user '{$user->email}'.");
                    continue;
                }

                // Trimitem reminder-ul
                broadcast(new EventReminderBroadcast($event, $user, $this->notificationService));
                $this->info("Reminder sent to participant '{$user->email}' for event '{$event->title}'.");

                // Salvăm cache-ul pentru utilizatorul respectiv pentru reminder
                Cache::put($userCacheKeyReminder, true, now()->addMinutes(10));  // Setăm cache-ul pentru 10 minute
            }
        }
    }
}
