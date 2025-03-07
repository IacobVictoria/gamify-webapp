<?php

namespace App\Strategies\Badges\Quizzes;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;

class QuizEnthusiastBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $quizResults = $user->quizResults()->where('is_locked', true)->get();
        return $quizResults->count() >= 5 && $quizResults->avg('percentage_score') >= 80;
    }

    public function getBadgeName(): string
    {
        return 'Quiz Enthusiast';
    }
}
