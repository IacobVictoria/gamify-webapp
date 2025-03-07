<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Events\FirstEventParticipationBadgeStrategy;
use App\Strategies\Badges\Events\ThreeEventsParticipationBadgeStrategy;

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