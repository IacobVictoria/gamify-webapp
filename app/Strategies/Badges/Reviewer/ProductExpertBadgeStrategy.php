<?php

namespace App\Strategies\Badges\Reviewer;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class ProductExpertBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        $reviewsLong = $user->reviews->filter(function ($review) {
            return strlen($review->description) > 100;
        });

        return $reviewsLong->count() > 20;
    }

    public function getBadgeName(): string
    {
        return 'Product Expert';
    }
}