<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\QuizLeaderboard\EachRankQuizTopBadgeStrategy;
use App\Strategies\Badges\QuizLeaderboard\FirstRankQuizTopBadgeStrategy;
use App\Strategies\Badges\QuizLeaderboard\SecondTimeFirstRankQuizTopBadgeStrategy;

class QuizLeaderboardBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new FirstRankQuizTopBadgeStrategy(),
            new SecondTimeFirstRankQuizTopBadgeStrategy(),
            new EachRankQuizTopBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}