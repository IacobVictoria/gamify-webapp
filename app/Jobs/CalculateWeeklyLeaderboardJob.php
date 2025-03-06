<?php

namespace App\Jobs;

use App\Events\UserMadeLeaderboardQuizEvent;
use App\Models\QuizLeaderboardHistory;
use App\Models\User;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CalculateWeeklyLeaderboardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected NotificationService $notificationService;
    protected UserScoreService $userScoreService;
    protected BadgeService $badgeService;

    public function __construct(NotificationService $notificationService, UserScoreService $userScoreService, BadgeService $badgeService)
    {
        $this->notificationService = $notificationService;
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
    }
    public function handle()
    {
        $leaderBoard = $this->calculateLeaderboard();
        Cache::put('weekly_leaderboard', $leaderBoard, now()->addWeek());

        foreach ($leaderBoard as $index => $rankedUser) {
            $this->processRankedUser($rankedUser, $index + 1);
        }
    }
    /**
     * Calculează leaderboard-ul săptămânal.
     */
    private function calculateLeaderboard(): array
    {
        return User::with('quizResults')->get()->map(function ($user) {
            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'gender' => $user->gender,
                'total_score' => $user->quizResults->sum('total_score'),
                'challenges' => $user->quizResults->unique('quiz_id')->count(),
            ];
        })->sortByDesc('total_score')->take(3)->values()->toArray();
    }
    
    /**
     * Procesează fiecare utilizator de pe leaderboard.
     */
    private function processRankedUser(array $rankedUser, int $rank)
    {
        $user = User::find($rankedUser['user_id']);

        if ($user) {
            $this->userScoreService->awardPointsBasedOnRank($user, $rank);

            QuizLeaderboardHistory::create([
                'id' => Uuid::uuid(),
                'user_id' => $rankedUser['user_id'],
                'rank' => $rank,
                'date' => now()->format('Y-m-d'),
                'points' => $this->userScoreService->getPointsForRank($rank),
            ]);

            $this->badgeService->quizLeaderboardBadges($user);
            broadcast(new UserMadeLeaderboardQuizEvent($user, $this->notificationService));
        }
    }
}
