<?php

namespace App\Strategies\Badges\Reviewer;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\Review;
use App\Models\User;
class PioneerBadgeStrategy implements BadgeStrategyInterface
{

    public function appliesTo(User $user): bool
    {
        $reviews = Review::orderBy('created_at', 'asc')->get()->groupBy('product_id');
        $userFirstReviewCount = 0;

        foreach ($reviews as $productReviews) {
            $firstReview = $productReviews->first();
            if ($firstReview->user_id === $user->id) {
                $userFirstReviewCount++;
            }
        }

        return $userFirstReviewCount > 5;
    }

    public function getBadgeName(): string
    {
        return 'Pioneer';
    }
}