<?php

namespace App\Strategies\Badges\Reviewer;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class TrustedReviewerBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        return $user->reviewlikes()->count() > 10;
    }

    public function getBadgeName(): string
    {
        return 'Trusted Reviewer';
    }
}