<?php

namespace App\Enums;

enum ActivityType : string
{
    case DIET = 'diet';
    case ARTICLE = 'article';
    case TIP = 'tip';

    public function label(): string
    {
        return match ($this) {
            self::DIET => 'Dietă',
            self::ARTICLE => 'Articol',
            self::TIP => 'Sfat Rapid',
        };
    }
}
