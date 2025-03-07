<?php

namespace App\Strategies\Badges\Quizzes;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
class QuizPerfectScoreBadgeStrategy implements BadgeStrategyInterface
{
    //Obținerea unui punctaj maxim (de exemplu, 100%) la 3 quiz uri
    public function appliesTo(User $user): bool
    {
        $quizResults = $user->quizResults()->where('is_locked', true)->get();
        return $quizResults->count() >= 3 && $quizResults->avg('percentage_score') === 100;
    }

    public function getBadgeName(): string
    {
        return 'Quiz Perfect Score';
    }
}