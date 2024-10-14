<?php

namespace App\Enums;

enum RatingTitle: string
{
    case ZERO = 'Dezamăgitor';
    case HALF = 'Foarte slab';
    case ONE = 'Rău';
    case ONE_HALF = 'Acceptabil';
    case TWO = 'Bun';
    case TWO_HALF = 'Foarte bun';
    case THREE = 'Excelent';
    case THREE_HALF = 'Impecabil';
    case FOUR = 'Extraordinar';
    case FOUR_HALF = 'Ceea ce m-am așteptat';
    case FIVE = 'Perfect';
}
