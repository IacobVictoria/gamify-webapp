<?php

namespace App\Services;

use App\Events\LeaderboardTop10Event;
use App\Models\User;
use App\Services\Badges\LeaderboardBadgeService;

class LeaderboardService
{
    protected $notificationService, $leaderboardBadgeService;

    public function __construct(NotificationService $notificationService, LeaderboardBadgeService $leaderboardBadgeService)
    {
        $this->notificationService = $notificationService;
        $this->leaderboardBadgeService = $leaderboardBadgeService;
    }

    public function checkAndNotifyLeaderboardEntry(User $user, int $previousPosition)
    {
        $newPosition = User::where('score', '>', $user->score)->count() + 1;

        // Dacă noua poziție este mai mică decât cea anterioară și este în Top 10, emitem evenimentul
        if ($newPosition < $previousPosition && $newPosition <= 10) {
            broadcast(new LeaderboardTop10Event($user, $newPosition, $this->notificationService));
        }

        // Verificăm și atribuim badge-uri leaderboard
        $this->leaderboardBadgeService->checkAndAssignBadges($user);
    }
}
