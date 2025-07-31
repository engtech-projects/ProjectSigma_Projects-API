<?php

namespace Database\Seeders;

use App\Models\ResourceName;
use Illuminate\Database\Seeder;

class ResourceNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResourceName::updateOrCreate(
            ['name' => 'Materials'],
            [
                'name' => 'Materials',
                'category' => 'inventory',
                'description' => 'Construction Materials',
            ]
        );

        ResourceName::updateOrCreate(
            ['name' => 'Labor'],
            [
                'name' => 'Labor',
                'category' => 'service',
                'description' => 'Labor Expense',
            ]
        );

        ResourceName::updateOrCreate(
            ['name' => 'Equipment'],
            [
                'name' => 'Equipment',
                'category' => 'service',
                'description' => 'Equipment Rental',
            ]
        );

        ResourceName::updateOrCreate(
            ['name' => 'Fuel / Oil Cost'],
            [
                'name' => 'Fuel / Oil Cost',
                'category' => 'service',
                'description' => 'Cost of fuel/oil for vehicles or machines used in operations',
            ]
        );

        ResourceName::updateOrCreate(
            ['name' => 'Overhead Cost'],
            [
                'name' => 'Overhead Cost',
                'category' => 'service',
                'description' => 'Indirect costs needed to support business operations',
            ]
        );
    }
}
