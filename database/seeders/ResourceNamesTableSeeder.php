<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ResourceNamesCategory;

class ResourceNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('resource_names')->insert([
            ['id' => 1, 'name' => 'materials', 'category' => ResourceNamesCategory::INVENTORY, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'labor_expense', 'category' => ResourceNamesCategory::SERVICE, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'equipment_rental', 'category' => ResourceNamesCategory::SERVICE, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'miscellaneous_cost', 'category' => ResourceNamesCategory::SERVICE, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'other_expenses', 'category' => ResourceNamesCategory::SERVICE, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
