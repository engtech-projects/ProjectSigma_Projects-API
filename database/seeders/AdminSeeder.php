<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permissions;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Super Admin']);

        $user = User::factory()->create([
                    'name' => 'admin',
                    'email' => 'tomhotboy26@hotmail.orgY',
                    'is_admin' => true,
                ]);

        $user->assignRole($role);
    }
}
