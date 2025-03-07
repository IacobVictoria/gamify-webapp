<?php

namespace App\Strategies\Badges\Hangman;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\HangmanSession;
use Carbon\Carbon;

class DailyPlayerBadgeStrategy  implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $sessionsDates = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->pluck('created_at')
            ->map(fn($date) => $date->toDateString())
            ->unique()
            ->sort()
            ->values();

        $consecutiveDays = 0;
        $previousDate = null;
        foreach ($sessionsDates as $date) {
            if ($date && Carbon::parse($date)->diffInDays($previousDate) == 1) {
                $consecutiveDays++;
            } else {
                $consecutiveDays = 1;
            }
            $previousDate = Carbon::parse($date);

            if ($consecutiveDays === 7) {
                return true;
            }
        }

        return false;
    }

    public function getBadgeName(): string
    {
        return 'Daily Player';
    }
}
