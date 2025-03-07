<?php

namespace App\Strategies\Badges\Commenter;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class TrustedCommenterBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        return $user->commentLikes()->count() >= 10;
    }

    public function getBadgeName(): string
    {
        return 'Trusted Commenter';
    }
}