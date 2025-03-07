<?php

namespace App\Strategies\Badges\Shopping;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class ActiveShoppingBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->orders()->count() > 10;
    }

    public function getBadgeName(): string
    {
        return 'Active Shopper';
    }
}