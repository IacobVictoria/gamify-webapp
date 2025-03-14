<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventService
{
    public function getAllEvents()
    {
        return Event::all();
    }

    public function getRecurringEvents()
    {
        return Event::where('is_recurring', 1)->whereNull('parent_event_id')->get()->keyBy('id');
    }

    public function getLastEvents($parentEvents)
    {
        return $parentEvents->map(function ($event) {
            $lastEvent = Event::find($event->last_recurring_event_id) ?? $event;
            $lastEvent->parent = $event;
            return $lastEvent;
        });
    }

    public function generateGhostEvents($lastEvents)
    {
        return $lastEvents->flatMap(function ($event) {
            $parentEvent = $event->parent ?? $event;
            $recurringInterval = $parentEvent->recurring_interval;

            if (!$event->next_occurrence) {
                return [];
            }

            $nextOccurrence = Carbon::parse($event->next_occurrence);
            $eventDuration = Carbon::parse($event->start)->diffInMinutes(Carbon::parse($event->end));

            $ghosts = [];
            for ($i = 1; $i <= 3; $i++) {
                $ghostStart = $nextOccurrence;
                $ghostEnd = $event->type === 'supplier_order' ? $ghostStart : $ghostStart->copy()->addMinutes($eventDuration);

                $ghosts[] = [
                    'id' => 'ghost-' . $event->id . "-$i",
                    'title' => '[Upcoming] ' . $event->title,
                    'description' => '(Planned Recurring Event)',
                    'start' => $ghostStart->format('Y-m-d H:i'),
                    'end' => $ghostEnd->format('Y-m-d H:i'),
                    'type' => $event->type,
                    'details' => $event->details,
                    'isGhost' => true,
                ];

                $nextOccurrence = $this->calculateNextOccurrence($ghostStart, $recurringInterval);
            }

            return $ghosts;
        });
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
