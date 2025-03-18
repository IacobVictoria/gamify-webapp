<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Event\FirstEventParticipationBadgeStrategy;
use App\Strategies\Badges\Event\ThreeEventsParticipationBadgeStrategy;

class EventBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new FirstEventParticipationBadgeStrategy(),
            new ThreeEventsParticipationBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}