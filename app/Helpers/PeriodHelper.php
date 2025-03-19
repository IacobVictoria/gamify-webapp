<?php
namespace App\Helpers;

use App\Enums\MeetingPeriod;
use Carbon\Carbon;

class PeriodHelper
{
    public static function getPeriodRange(string $period, Carbon $meetingDate): array
    {
        return match ($period) {
            MeetingPeriod::LAST_MONTH->value => [
                'start_date' => $meetingDate->copy()->subMonth(),
                'end_date' => $meetingDate,
            ],
            MeetingPeriod::LAST_2_MONTHS->value => [
                'start_date' => $meetingDate->copy()->subMonths(2),
                'end_date' => $meetingDate,
            ],
            MeetingPeriod::LAST_3_MONTHS->value => [
                'start_date' => $meetingDate->copy()->subMonths(3),
                'end_date' => $meetingDate,
            ],
            MeetingPeriod::LAST_6_MONTHS->value => [
                'start_date' => $meetingDate->copy()->subMonths(6),
                'end_date' => $meetingDate,
            ],
            MeetingPeriod::LAST_YEAR->value => [
                'start_date' => $meetingDate->copy()->subYear(),
                'end_date' => $meetingDate,
            ],
            default => [
                'start_date' => $meetingDate->copy()->subMonth(),
                'end_date' => $meetingDate,
            ],
        };
    }
}
