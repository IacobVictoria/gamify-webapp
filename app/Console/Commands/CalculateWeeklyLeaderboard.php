<?php

namespace App\Console\Commands;

use App\Events\UserMadeLeaderboardEvent;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CalculateWeeklyLeaderboard extends Command
{
    protected $signature = 'leaderboard:calculate-weekly';
    protected $description = 'Calculează leaderboard-ul săptămânal și trimite notificări';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
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
            foreach ($leaderBoard as $rankedUser) {
                $user = User::find($rankedUser['user_id']);
                if ($user) {
                    broadcast(new UserMadeLeaderboardEvent($user, $this->notificationService));
                }
            }
    

    }
}
