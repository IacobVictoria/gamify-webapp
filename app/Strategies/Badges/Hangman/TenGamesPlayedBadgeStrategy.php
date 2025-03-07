<?php

namespace App\Strategies\Badges\Hangman;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\HangmanSession;

class TenGamesPlayedBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->count() >= 10;
    }

    public function getBadgeName(): string
    {
        return '10 Games Played';
    }
}
