<?php

namespace App\Strategies\Badges\Quizzes;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class QuizNoviceBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return $user->quizResults()->where('is_locked', true)->count() === 1;
    }

    public function getBadgeName(): string
    {
        return 'Quiz Novice';
    }
}
