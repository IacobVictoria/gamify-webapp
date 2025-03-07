<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Commenter\ActiveCommenterBadgeStrategy;
use App\Strategies\Badges\Commenter\TrustedCommenterBadgeStrategy;

class CommenterBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new ActiveCommenterBadgeStrategy(),
            new TrustedCommenterBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}