<?php

namespace App\Strategies\Badges\Reviewer;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class TopReviewerBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        return $user->reviews()->count() >= 2;
    }

    public function getBadgeName(): string
    {
        return 'Top Reviewer';
    }
}