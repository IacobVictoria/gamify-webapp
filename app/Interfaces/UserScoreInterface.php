<?php

namespace App\Interfaces;
use App\Models\User;
interface UserScoreInterface
{
    public function addScore(User $user, $score);

    public function updateScore(User $user, $score);
}