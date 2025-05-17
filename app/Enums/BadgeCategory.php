<?php

namespace App\Enums;

enum BadgeCategory: string
{
    case REVIEWER = 'reviewer';
    case COMMENTER = 'commenter';
    case SHOPPING = 'shopping';
    case QUIZ = 'quiz';
    case QUIZ_LEADERBOARD = 'quiz-leaderboard';
    case HANGMAN = 'hangman';
    case LEADERBOARD = 'leaderboard';
    case QRSCAN = 'qr-scan-product';

    public function label(): string
    {
        return match ($this) {
            self::REVIEWER => 'Reviewer',
            self::COMMENTER => 'Commenter',
            self::SHOPPING => 'Shopping',
            self::QUIZ => 'Quiz',
            self::QUIZ_LEADERBOARD => 'Quiz Leaderboard',
            self::HANGMAN => 'Hangman',
            self::LEADERBOARD => 'Leaderboard',
            self::QRSCAN => 'QR Scan Product',
        };
    }
}
