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
        ResourceName::updateOrCreate([
            'name' => 'Materials',
            'category' => 'inventory',
            'description' => 'Construction Materials',
        ]);

        ResourceName::updateOrCreate([
            'name' => 'Labor',
            'category' => 'service',
            'description' => 'Labor Expense',
        ]);

        ResourceName::updateOrCreate([
            'name' => 'Equipment',
            'category' => 'service',
            'description' => 'Equipment Rental',
        ]);
    }
}
