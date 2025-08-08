<?php

namespace App\Enums;

enum ResourceType: string
{
    case MATERIALS = 'materials';
    case LABOR_EXPENSE = 'labor_expense';
    case EQUIPMENT_RENTAL = 'equipment_rental';
    case MISCELLANEOUS_COST = 'miscellaneous_cost';
    case OTHER_EXPENSES = 'other_expenses';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
