<?php

namespace App\Strategies\Badges\Quizzes;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Enums\UserQuizDifficulty;

class QuizExplorerBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $categories = UserQuizDifficulty::cases();
        $completedQuizzes = $user->quizResults()->with('quiz')->get()->pluck('quiz.difficulty')->unique();
        return count($completedQuizzes) === count($categories);
    }

    public function getBadgeName(): string
    {
        return 'Quiz Explorer';
    }
}
