<?php

namespace App\Enums;

enum MeetingPeriod :string
{
    case LAST_MONTH = 'ultima_luna';
    case LAST_2_MONTHS = 'ultimele_2_luni';
    case LAST_3_MONTHS = 'ultimele_3_luni';
    case LAST_6_MONTHS = 'ultimele_6_luni';
    case LAST_YEAR = 'ultimul_an';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
