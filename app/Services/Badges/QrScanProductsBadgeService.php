<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\QrScanProduct\FirstScanBadgeStrategy;
use App\Strategies\Badges\QrScanProduct\ScanMasterBadgeStrategy;
use App\Strategies\Badges\QrScanProduct\ScanStreakBadgeStrategy;

class QrScanProductsBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new FirstScanBadgeStrategy(),
            new ScanStreakBadgeStrategy(),
            new ScanMasterBadgeStrategy()

        ];
        parent::__construct($badgeAssigner, $rules);
    }
}