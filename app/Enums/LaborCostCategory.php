<?php
namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum LaborCostCategory: string
{
    use EnumHelper;
    case THIRTEENTH_MONTH = '13th_month';
    case GOVERNMENT_PREMIUMS = 'government_premiums';
    case PROJECT_ALLOWANCE = 'project_allowance';
}
