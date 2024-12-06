<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permissions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
                    'id' => 1,
                    'user_id' => 1,
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'is_admin' => true
                ]);
    }
}
