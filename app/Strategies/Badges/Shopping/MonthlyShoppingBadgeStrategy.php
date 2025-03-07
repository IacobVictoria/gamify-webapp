<?php

namespace App\Strategies\Badges\Shopping;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class MonthlyShoppingBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->orders()
            ->selectRaw('COUNT(*) as order_count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->groupBy('year', 'month')
            ->havingRaw('order_count > 2')
            ->count() > 0;
    }

    public function getBadgeName(): string
    {
        return 'Monthly Shopper';
    }
}