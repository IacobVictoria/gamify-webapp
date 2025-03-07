<?php

namespace App\Strategies\Badges\Events;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class ThreeEventsParticipationBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->participants()->where('confirmed', true)->count() === 3;
    }

    public function getBadgeName(): string
    {
        return 'Three Events Participation';
    }
}
