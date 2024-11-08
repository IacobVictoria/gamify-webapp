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
    }

    public function updateScore(User $user, $score)
    {
        $user->score += $score;
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
}