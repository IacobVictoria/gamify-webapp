<?php

namespace App\Strategies\Badges\Commenter;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class ActiveCommenterBadgeStrategy  implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        return $user->reviewComments()->count() >= 2;
    }

    public function getBadgeName(): string
    {
        return 'Active Commenter';
    }
}