<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum WeekDays: string
{
    use EnumHelper;
    case MONDAY = 'm';
    case TUESDAY = 't';
    case WEDNESDAY = 'w';
    case THURSDAY = 'th';
    case FRIDAY = 'f';
    case SATURDAY = 'sa';
    case SUNDAY = 'su';
}
