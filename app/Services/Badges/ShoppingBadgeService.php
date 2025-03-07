<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Shopping\ActiveShoppingBadgeStrategy;
use App\Strategies\Badges\Shopping\MonthlyShoppingBadgeStrategy;

class ShoppingBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new ActiveShoppingBadgeStrategy(),
            new MonthlyShoppingBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}