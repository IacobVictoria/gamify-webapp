<?php

namespace App\Services;
use App\Events\UserScoreUpdatedEvent;
use App\Interfaces\UserScoreInterface;
use App\Models\User;

class UserScoreService implements UserScoreInterface
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function addScore(User $user, $score)
    {
        $user->score += $score;
        $user->save();
        broadcast(new UserScoreUpdatedEvent($user, $score, "Ai primit " . $score . " !", $this->notificationService));
    }

    public function updateScore(User $user, $score)
    {
        $user->score = $score;
        $user->save();
    }

    public function quizAttemptScore(User $user, $nr_attempt, $obtained_score)
    {
        $multiplier = match ($nr_attempt) {
            1 => 1.0,
            2 => 0.8,
            3 => 0.6
        };

        $final_score = $obtained_score * $multiplier;

        $user->score += $final_score;
        $user->save();

        broadcast(new UserScoreUpdatedEvent($user, $final_score, "Quiz ul completat ti-a adus puncte!", $this->notificationService));

    }

    public function awardPointsBasedOnRank(User $user, int $rank)
    {
        $points = $this->getPointsForRank($rank);

        // Award points based on rank
        $this->addScore($user, $points);

        // Broadcast the event for score update
        broadcast(new UserScoreUpdatedEvent($user, $points, "Ai primit puncte pentru locul #$rank pe leaderboard!", $this->notificationService));
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

    public function awardPointsBasedOnHangmanScore(?User $user, int $finalScore)
    {
        $points = match (true) {
            $finalScore >= 90 => 50,
            $finalScore >= 80 => 40,
            $finalScore >= 70 => 30,
            $finalScore >= 60 => 20,
            $finalScore >= 50 => 10,
            default => 5,
        };

        $this->addScore($user, $points);
    }

}