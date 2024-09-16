<?php

namespace App\Interfaces;
use App\Models\User;
interface UserAchievementInterface
{
    public function checkAndSendMedalEmail($user, int $newScore, int $oldScore);
}