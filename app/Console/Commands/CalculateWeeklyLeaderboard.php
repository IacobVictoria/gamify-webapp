<?php

namespace App\Console\Commands;

use App\Jobs\CalculateWeeklyLeaderboardJob;
use App\Services\Badges\QuizLeaderboardBadgeService;
use App\Services\NotificationService;
use App\Services\UserScoreService;
use Illuminate\Console\Command;

class CalculateWeeklyLeaderboard extends Command
{
    protected NotificationService $notificationService;
    protected UserScoreService $userScoreService;
    protected QuizLeaderboardBadgeService $badgeService;

    protected $signature = 'leaderboard:calculate-weekly';
    protected $description = 'Calculează leaderboard-ul săptămânal și trimite notificări';

    public function __construct(NotificationService $notificationService, UserScoreService $userScoreService, QuizLeaderboardBadgeService $badgeService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
    }


    public function handle()
    {
        dispatch(new CalculateWeeklyLeaderboardJob($this->notificationService, $this->userScoreService, $this->badgeService));

    }
}
