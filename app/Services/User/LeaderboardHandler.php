<?php
namespace App\Services\User;

use App\Models\User;
use App\Services\LeaderboardService;


class LeaderboardHandler extends AbstractScoreHandler
{
    public function handle(User $user, int $score): void
    {
        $previousPosition = User::where('score', '>', $user->score)->count() + 1;

        // Lazy load → nu se instanțiază decât la nevoie
        app(LeaderboardService::class)->checkAndNotifyLeaderboardEntry($user, $previousPosition);

        $this->next($user, $score);
    }
}
