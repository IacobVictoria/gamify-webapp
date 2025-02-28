<?php

namespace App\Enums;

enum BadgeCategory: string
{
    case REVIEWER = 'reviewer';
    case COMMENTER = 'commenter';
    case SHOPPING = 'shopping';
    case QUIZ = 'quiz';
    case EVENT = 'event';
    case QUIZ_LEADERBOARD = 'quiz-leaderboard';
    case HANGMAN = 'hangman';
    case LEADERBOARD = 'leaderboard';
}
