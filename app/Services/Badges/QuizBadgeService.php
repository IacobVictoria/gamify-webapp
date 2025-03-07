<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Quizzes\QuizEnthusiastBadgeStrategy;
use App\Strategies\Badges\Quizzes\QuizExplorerBadgeStrategy;
use App\Strategies\Badges\Quizzes\QuizNoviceBadgeStrategy;
use App\Strategies\Badges\Quizzes\QuizPerfectScoreBadgeStrategy;


class QuizBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new QuizNoviceBadgeStrategy(),
            new QuizEnthusiastBadgeStrategy(),
            new QuizExplorerBadgeStrategy(),
            new QuizPerfectScoreBadgeStrategy(),
        ];
        parent::__construct($badgeAssigner, $rules);
    }
}