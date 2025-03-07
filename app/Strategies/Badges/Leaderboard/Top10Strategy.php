<?php
namespace App\Strategies\Badges\Leaderboard;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class Top10Strategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $userPosition = User::where('score', '>', $user->score)->count() + 1;
        return $userPosition <= 10;
    }

    public function getBadgeName(): string
    {
        return 'Rising Star';
    }
}
