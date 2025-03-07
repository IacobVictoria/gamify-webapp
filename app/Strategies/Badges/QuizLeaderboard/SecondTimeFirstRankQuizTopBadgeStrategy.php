<?php

namespace App\Strategies\Badges\QuizLeaderboard;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class SecondTimeFirstRankQuizTopBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->quizLeaderboardHistory()->where('rank', 1)->count() === 2;
    }

    public function getBadgeName(): string
    {
        return 'Second Time First Rank in Quiz Top';
    }
}
