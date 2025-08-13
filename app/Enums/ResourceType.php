<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ResourceType: string
{
    use EnumHelper;
    case MATERIALS = 'materials';
    case LABOR_EXPENSE = 'labor_expense';
    case EQUIPMENT_RENTAL = 'equipment_rental';
    case MISCELLANEOUS_COST = 'miscellaneous_cost';
    case OTHER_EXPENSES = 'other_expenses';
}
