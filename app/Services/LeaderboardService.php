<?php

namespace App\Services;

use App\Events\LeaderboardTop10Event;
use App\Models\User;

class LeaderboardService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function checkAndNotifyLeaderboardEntry(User $user, int $previousPosition)
    {
        $newPosition = User::where('score', '>', $user->score)->count() + 1;

        // Dacă noua poziție este mai mică decât cea anterioară și este în Top 10, emitem evenimentul
        if ($newPosition < $previousPosition && $newPosition <= 10) {
            broadcast(new LeaderboardTop10Event($user, $newPosition, $this->notificationService));
        }
    }
}
