<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Leaderboard\ElitePlayerStrategy;
use App\Strategies\Badges\Leaderboard\PodiumWinnerStrategy;
use App\Strategies\Badges\Leaderboard\Top10Strategy;

class LeaderboardBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new PodiumWinnerStrategy(),
            new Top10Strategy(),
            new ElitePlayerStrategy()
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}