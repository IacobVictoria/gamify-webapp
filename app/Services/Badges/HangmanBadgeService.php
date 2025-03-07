<?php

namespace App\Services\Badges;

use App\Interfaces\BadgeAssignerInterface;
use App\Strategies\Badges\Hangman\DailyPlayerBadgeStrategy;
use App\Strategies\Badges\Hangman\FirstGamePlayedBadgeStrategy;
use App\Strategies\Badges\Hangman\NearPerfectGuesserBadgeStrategy;
use App\Strategies\Badges\Hangman\PerfectGuesserBadgeStrategy;
use App\Strategies\Badges\Hangman\TenGamesPlayedBadgeStrategy;


class HangmanBadgeService extends AbstractBadgeCategoryService
{
    public function __construct(BadgeAssignerInterface $badgeAssigner)
    {
        $rules = [
            new FirstGamePlayedBadgeStrategy(),
            new TenGamesPlayedBadgeStrategy(),
            new PerfectGuesserBadgeStrategy(),
            new NearPerfectGuesserBadgeStrategy(),
            new DailyPlayerBadgeStrategy(),
        ];

        parent::__construct($badgeAssigner, $rules);
    }
}
