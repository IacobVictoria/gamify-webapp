<?php

namespace App\Strategies\Badges\Hangman;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\HangmanSession;

class FirstGamePlayedBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->exists();
    }

    public function getBadgeName(): string
    {
        return 'First Game Played';
    }
}
