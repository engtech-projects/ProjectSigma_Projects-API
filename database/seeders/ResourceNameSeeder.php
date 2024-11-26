<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceName;

class ResourceNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

		ResourceName::create([
			'name' => 'Materials',
			'category' => 'inventory',
			'description' => 'Construction Materials',
		]);

		ResourceName::create([
			'name' => 'Labor',
			'category' => 'service',
			'description' => 'Labor Expense',
		]);

		ResourceName::create([
			'name' => 'Equipment',
			'category' => 'service',
			'description' => 'Equipment Rental',
		]);
		
    }
}
