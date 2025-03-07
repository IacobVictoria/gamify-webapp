<?php
namespace App\Strategies\Badges\Leaderboard;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class PodiumWinnerStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $userPosition = User::where('score', '>', $user->score)->count() + 1;
        return $userPosition <= 3;
    }

    public function getBadgeName(): string
    {
        return 'Podium Winner';
    }
}
