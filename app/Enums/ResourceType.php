<?php
namespace App\Enums;
use App\Enums\Traits\EnumHelper;
enum ResourceType: string
{
    use EnumHelper;
    case MATERIALS = 'materials';
    case LABOR_EXPENSE = 'labor_expense';
    case EQUIPMENT_RENTAL = 'equipment_rental';
    case GOVERNMENT_PREMIUMS = 'government_premiums';
    case PROJECT_ALLOWANCE = 'project_allowance';
    case MISCELLANEOUS_COST = 'miscellaneous_cost';
    case OTHER_EXPENSES = 'other_expenses';
    case FUEL_OIL_COST = 'fuel_oil_cost';
    case OVERHEAD_COST = 'overhead_cost';
    public function displayName(): string
    {
        return match ($this) {
            self::MATERIALS => 'Materials',
            self::LABOR_EXPENSE => 'Labor',
            self::EQUIPMENT_RENTAL => 'Equipment',
            self::GOVERNMENT_PREMIUMS => 'SSS/PHIC/HMDF PREMIUMS',
            self::PROJECT_ALLOWANCE => 'Project Allowance',
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
