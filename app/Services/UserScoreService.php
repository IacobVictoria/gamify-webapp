<?php

namespace App\Services;
use App\Interfaces\UserScoreInterface;
use App\Models\User;

class UserScoreService implements UserScoreInterface
{
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
}