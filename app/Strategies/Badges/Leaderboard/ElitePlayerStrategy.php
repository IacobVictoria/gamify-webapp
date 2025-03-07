<?php
namespace App\Strategies\Badges\Leaderboard;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class ElitePlayerStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->score >= 1000;
    }

    public function getBadgeName(): string
    {
        return 'Elite Player';
    }
}
