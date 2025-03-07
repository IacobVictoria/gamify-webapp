<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Reviewer\ActiveReviewerBadgeStrategy;
use App\Strategies\Badges\Reviewer\PioneerBadgeStrategy;
use App\Strategies\Badges\Reviewer\ProductExpertBadgeStrategy;
use App\Strategies\Badges\Reviewer\TopReviewerBadgeStrategy;
use App\Strategies\Badges\Reviewer\TrustedReviewerBadgeStrategy;

class ReviewerBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new TopReviewerBadgeStrategy(),
            new ActiveReviewerBadgeStrategy(),
            new ProductExpertBadgeStrategy(),
            new TrustedReviewerBadgeStrategy(),
            new PioneerBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}