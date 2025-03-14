<?php
namespace App\Jobs;

use App\Models\Event;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRecurringEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function handle(): void
    {
        $event = Event::find($this->event->id);

        if (!$event || !$event->is_recurring) {
            return; // Ieși din job dacă evenimentul nu mai există sau nu mai este recurent
        }

        $nextOccurrence = Carbon::parse($this->event->next_occurrence);
        $startTime = Carbon::parse($this->event->start);
        $endTime = Carbon::parse($this->event->end);
        $eventDuration = $startTime->diff($endTime);

        $newEnd = $this->event->type === 'supplier_order'
            ? $nextOccurrence // Pentru comenzi, end = nextOccurrence (1 singură zi)
            : $nextOccurrence->copy()->add($eventDuration); // Pentru discounturi, end = start + durata

        $newEvent = Event::create([
            'id' => Uuid::uuid(),
            'title' => $this->event->title,
            'description' => $this->event->description,
            'parent_event_id' => $this->event->parent_event_id ?? $this->event->id,
            'start' => $nextOccurrence->format('Y-m-d H:i'),
            'end' => $newEnd->format('Y-m-d H:i'),
            'type' => $this->event->type,
            'is_published' => 1,
            'next_occurrence' => $this->calculateNextOccurrence($nextOccurrence, $this->event->recurring_interval)->format('Y-m-d H:i'),
            'details' => $this->event->details,
            'calendarId' => $this->event->calendarId,
        ]);

        // Actualizează evenimentul original pentru următoarea apariție
        $this->event->update([
            'next_occurrence' => $newEvent->next_occurrence,
            'last_recurring_event_id' => $newEvent->id
        ]);

        // Nu mai actualizăm evenimentul original, ci lansăm job-ul pentru NOUL eveniment
        self::dispatch($this->event)->delay($newEvent->next_occurrence);
    }

    private function calculateNextOccurrence($currentDate, $interval)
    {
        return match ($interval) {
            'daily' => $currentDate->addDay(),
            'weekly' => $currentDate->addWeek(),
            'monthly' => $currentDate->addMonth(),
            default => null
        };
    }
}
