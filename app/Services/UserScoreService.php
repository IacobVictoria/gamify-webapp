<?php

namespace App\Services;
use App\Events\LeaderboardTop10Event;
use App\Events\UserMedalAwardedEvent;
use App\Events\UserScoreUpdatedEvent;
use App\Interfaces\UserScoreInterface;
use App\Models\Medal;
use App\Models\User;
use App\Services\User\BroadcastScoreHandler;
use App\Services\User\LeaderboardHandler;
use App\Services\User\MedalHandler;
use App\Services\User\SaveScoreHandler;
use App\Services\User\ScoreHandlerInterface;

class UserScoreService implements UserScoreInterface
{
    protected ScoreHandlerInterface $handlerChain;

    public function __construct(
        SaveScoreHandler $saveHandler,
        LeaderboardHandler $leaderboardHandler,
        MedalHandler $medalHandler,
        BroadcastScoreHandler $broadcastHandler
    ) {
        $saveHandler
            ->setNext($leaderboardHandler)
            ->setNext($medalHandler)
            ->setNext($broadcastHandler);

        $this->handlerChain = $saveHandler;
    }

    public function addScore(User $user, $score)
    {
        $this->handlerChain->handle($user, $score);
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

        $this->addScore($user, $final_score);

    }

    public function awardPointsBasedOnRank(User $user, int $rank)
    {
        $points = $this->getPointsForRank($rank);

        // Award points based on rank
        $this->addScore($user, $points);
    }

    public function getPointsForRank(int $rank): int
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