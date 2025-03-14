<?php

namespace App\Console\Commands;

use App\Events\UserMadeLeaderboardQuizEvent;
use App\Jobs\CalculateWeeklyLeaderboardJob;
use App\Models\QuizLeaderboardHistory;
use App\Models\User;
use App\Services\Badges\QuizLeaderboardBadgeService;
use App\Services\NotificationService;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
