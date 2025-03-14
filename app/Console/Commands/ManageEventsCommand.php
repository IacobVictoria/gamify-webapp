<?php

namespace App\Console\Commands;

use App\Events\EventReminderBroadcast;
use App\Events\NewEventBroadcast;
use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Interfaces\PdfGeneratorServiceInterface;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ManageEventsCommand extends Command
{
    protected $signature = 'app:manage-events-command';
    protected $description = 'Manage events - close past events, notify users about new events and send reminders to participants';
    protected $notificationService;
    protected $pdfGenerator;


    public function __construct(NotificationService $notificationService, )
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
        //daca a trecut perioada lor , atunci pun CLOSED, ei pot sa scaneze sa intre si in timpul evenimentului
        $events = Event::where('end', '<', Carbon::now())->where('status', '!=', 'CLOSED')->where('type', 'event')->get();

        foreach ($events as $event) {
            $event->status = 'CLOSED';
            $event->save();
            // Generăm raportul PDF cu lista participanților
            $this->generateParticipantsListReport($event);

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
    protected function generateParticipantsListReport($event)
    {
        $filename = "participants_event_{$event->id}.pdf";

        $storageStrategy = StorageStrategyFactory::create('s3');

        $generator = PdfGeneratorFactory::create('participants', $storageStrategy);
        
        $participants = $event->participants->map(function ($participant) {
            return [
                'name' => $participant->user->name,
                'email' => $participant->user->email,
                'confirmed' => $participant->confirmed,
            ];
        })->toArray();

        $confirmedCount = $event->participants->where('confirmed', 1)->count();
        $notConfirmedCount = $event->participants->where('confirmed', 0)->count();
        $totalParticipants = $event->participants->count();
        $confirmationPercentage = $totalParticipants > 0 ? ($confirmedCount / $totalParticipants) * 100 : 0;


        $filePath = $generator->generatePdf([
            'event' => [
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start,
                'end' => $event->end,
            ],
            'participants' => $participants,
            'filename' => $filename,
            'confirmedCount' => $confirmedCount,
            'notConfirmedCount' => $notConfirmedCount,
            'confirmationPercentage' => $confirmationPercentage,
            'totalParticipants' => $totalParticipants
        ]);
        

        Report::create([
            'id' => Uuid::uuid(),
            'type' => 'participants',
            'title' => "Lista Participanților - {$event->title}",
            's3_path' => $filePath,
        ]);

        $this->info("PDF report for event '{$event->title}' generated and saved at '{$filePath}'.");
    }
    protected function notifyUsersAboutNewEvent()
    {
        // Găsim toate evenimentele viitoare
        $events = Event::where('start', '>=', Carbon::now())->where('type', 'event')->where('is_published', true)->orderBy('start')->get();

        foreach ($events as $event) {
            // Verificăm dacă notificarea pentru acest eveniment a fost deja trimisă
            $users = User::all();
            foreach ($users as $user) {
                if ($user->hasRole('User')) {
                    $userCacheKey = 'user_' . $user->id . '_new_event_notification_sent_' . $event->id;
                    // Dacă notificarea a fost deja trimisă pentru acest utilizator, o să o sărim
                    if (Cache::has($userCacheKey)) {
                        continue;
                    }
                    // Trimitem notificarea doar dacă nu a fost trimisă deja
                    broadcast(new NewEventBroadcast($event, $this->notificationService, $user));

                    // Salvăm cache-ul pentru utilizatorul respectiv pentru acest eveniment
                    Cache::put($userCacheKey, true);
                }
            }
        }
    }


    protected function sendEventReminder()
    {
        // Find events starting in 10 minutes
        $events = Event::where('start', '<=', Carbon::now()->addMinutes(10))
            ->where('start', '>=', Carbon::now())
            ->where('status', '!=', 'CLOSED')
            ->where('is_published', true)
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
