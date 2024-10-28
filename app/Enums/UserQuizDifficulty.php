<?php

namespace App\Enums;

enum UserQuizDifficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';

    public function label(): string
    {
        return match ($this) {
            self::Easy => 'Easy',
            self::Medium => 'Medium',
            self::Hard => 'Hard',
        };
    }

    /**
     *  a color associated with each difficulty level.
     */
    public function color(): string
    {
        return match ($this) {
            self::Easy => 'green',
            self::Medium => 'orange',
            self::Hard => 'red',
        };
    }
}
