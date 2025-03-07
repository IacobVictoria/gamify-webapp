<?php

namespace App\Strategies\Badges\Reviewer;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class ActiveReviewerBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        return $user->reviews()
            ->selectRaw('WEEK(created_at) as review_week, COUNT(*) as review_count')
            ->where('created_at', '>=', now()->subMonths(3))
            ->groupBy('review_week')
            ->havingRaw('review_count >= 1')
            ->count() >= 5;
    }

    public function getBadgeName(): string
    {
        return 'Active Reviewer';
    }
}