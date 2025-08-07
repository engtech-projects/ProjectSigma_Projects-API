<?php

namespace App\Enums;

enum WorkTimeCategory: string
{
    case REGULAR = 'regular';
    case OVERTIME = 'overtime';
    case SUNDAY = 'sunday';
    case REGULAR_HOLIDAY = 'regular_holiday';
    case SPECIAL_HOLIDAY = 'special_holiday';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
