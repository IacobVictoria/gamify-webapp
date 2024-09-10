<?php

namespace App\Enums;

enum OrderStatus : string
{
        case Pending = 'Pending';
        case Processing = 'Processing';
        case Completed = 'Completed';
        case Canceled = 'Canceled';
}
