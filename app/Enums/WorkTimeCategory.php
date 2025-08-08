<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum WorkTimeCategory: string
{
    case REGULAR = 'regular';
    case OVERTIME = 'overtime';
    case SUNDAY = 'sunday';
    case REGULAR_HOLIDAY = 'regular_holiday';
    case SPECIAL_HOLIDAY = 'special_holiday';

    use EnumHelper;
}
