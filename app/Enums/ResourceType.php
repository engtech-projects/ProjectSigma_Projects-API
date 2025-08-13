<?php

namespace App\Enums;

enum ResourceType: string
{
    case MATERIALS = 'materials';
    case LABOR_EXPENSE = 'labor_expense';
    case EQUIPMENT_RENTAL = 'equipment_rental';
    case MISCELLANEOUS_COST = 'miscellaneous_cost';
    case OTHER_EXPENSES = 'other_expenses';
    case FUEL_OIL_COST = 'fuel_oil_cost';
    case OVERHEAD_COST = 'overhead_cost';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function displayName(): string
    {
        return match ($this) {
            self::MATERIALS => 'Materials',
            self::LABOR_EXPENSE => 'Labor',
            self::EQUIPMENT_RENTAL => 'Equipment',
            self::MISCELLANEOUS_COST => 'Miscellaneous Cost',
            self::OTHER_EXPENSES => 'Other Expenses',
            self::FUEL_OIL_COST => 'Fuel / Oil Cost',
            self::OVERHEAD_COST => 'Overhead Cost',
        };
    }

    public static function displayNames(): array
    {
        return array_map(fn ($case) => $case->displayName(), self::cases());
    }
}
