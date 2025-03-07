<?php

namespace App\Strategies\Badges\QuizLeaderboard;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class EachRankQuizTopBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $maxRank = 3;
        $userRanks = $user->quizLeaderboardHistory()->pluck('rank')->unique();

        return $userRanks->count() === $maxRank;
    }

    public function getBadgeName(): string
    {
        return 'Each Rank in Quiz Top';
    }
}
