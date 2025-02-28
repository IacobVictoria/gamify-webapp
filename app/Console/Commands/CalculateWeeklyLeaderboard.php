<?php

namespace App\Console\Commands;

use App\Events\UserMadeLeaderboardQuizEvent;
use App\Models\QuizLeaderboardHistory;
use App\Models\User;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CalculateWeeklyLeaderboard extends Command
{
    protected $signature = 'leaderboard:calculate-weekly';
    protected $description = 'Calculează leaderboard-ul săptămânal și trimite notificări';

    protected $notificationService;
    protected $userScoreService, $badgeService;

    public function __construct(NotificationService $notificationService, UserScoreService $userScoreService, BadgeService $badgeService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leaderBoard = User::with('quizResults')->get()->map(function ($user) {
            $totalScore = $user->quizResults->sum('total_score');
            $challenges = $user->quizResults->unique('quiz_id')->count();

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'gender' => $user->gender,
                'total_score' => $totalScore,
                'challenges' => $challenges,
            ];
        })
            ->sortByDesc('total_score')
            ->take(3)
            ->values()
            ->toArray();
        Cache::put('weekly_leaderboard', $leaderBoard, now()->addWeek());
        //event for leaderboard
        foreach ($leaderBoard as $index => $rankedUser) {
            $user = User::find($rankedUser['user_id']);
            if ($user) {
                $rank = $index + 1;
                $this->userScoreService->awardPointsBasedOnRank($user, $rank);
                QuizLeaderboardHistory::create([
                    'id' => Uuid::uuid(),
                    'user_id' => $rankedUser['user_id'],
                    'rank' => $rank,
                    'date' => now()->format('Y-m-d'),
                    'points' => $this->getPointsForRank($rank),
                ]);
                $this->badgeService->quizLeaderboardBadges($user);
                broadcast(new UserMadeLeaderboardQuizEvent($user, $this->notificationService));
            }
        }
    }
    protected function getPointsForRank(int $rank): int
    {
        switch ($rank) {
            case 1:
                return 50; // Rank 1 gets 50 points
            case 2:
                return 30; // Rank 2 gets 30 points
            case 3:
                return 15; // Rank 3 gets 15 points
            default:
                return 0;
        }
    }
}
