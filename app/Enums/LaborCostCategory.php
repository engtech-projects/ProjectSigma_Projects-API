<?php

namespace App\Enums;

enum LaborCostCategory: string
{
    case THIRTEENTH_MONTH = '13th_month';
    case GOVERNMENT_PREMIUMS = 'government_premiums';
    case PROJECT_ALLOWANCE = 'project_allowance';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
