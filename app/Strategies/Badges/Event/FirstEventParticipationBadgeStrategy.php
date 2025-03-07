<?php

namespace App\Strategies\Badges\Events;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class FirstEventParticipationBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->participants()->where('confirmed', true)->count() === 1;
    }

    public function getBadgeName(): string
    {
        return 'First Event Participation';
    }
}
