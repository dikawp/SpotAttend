<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 2: Superadmin
        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@spotattend.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 2,
        ]);

        // 1: HR
        User::create([
            'name' => 'HR Manager',
            'email' => 'admin@spotattend.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 1,
        ]);

        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
