<?php

namespace App\Strategies\Badges\Hangman;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\HangmanSession;

class PerfectGuesserBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->get()
            ->every(function ($session) use ($user) {
                return $session->creator_id === $user->id
                    ? $session->mistakes_creator === 0
                    : $session->mistakes_opponent === 0;
            });
    }

    public function getBadgeName(): string
    {
        return 'Perfect Guesser';
    }
}
