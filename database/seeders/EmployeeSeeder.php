<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $departments = Department::all();
        $positions = Position::all();

        // Demo Employee
        $demoDepartment = $departments->random();
        $demoPositions = $positions->where('department_id', $demoDepartment->id);
        $demoPosition = $demoPositions->isNotEmpty() ? $demoPositions->random() : $positions->random();

        $demoUser = User::create([
            'name' => 'Karyawan Demo',
            'email' => 'karyawan@spotattend.com',
            'password' => Hash::make('password'),
            'role' => 0,
        ]);

        Employee::create([
            'user_id'        => $demoUser->id,
            'nik'            => 'DEMO001',
            'full_name'      => $demoUser->name,
            'place_of_birth' => 'Jakarta',
            'date_of_birth'  => '1995-01-01',
            'gender'         => 'Male',
            'marital_status' => 'Single',
            'address'        => 'Jl. Demo No. 1',
            'phone_number'   => '081234567890',
            'hire_date'      => '2023-01-01',
            'position_id'    => $demoPosition->id,
            'department_id'  => $demoDepartment->id,
            'photo'          => null,
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $department = $departments->random();
            $availablePositions = $positions->where('department_id', $department->id);
            $position = $availablePositions->isNotEmpty()
                ? $availablePositions->random()
                : $positions->random();

            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
            ]);

            Employee::create([
                'user_id'        => $user->id,
                'nik'            => strtoupper(substr($department->name, 0, 2)) . str_pad($i, 3, '0', STR_PAD_LEFT),
                'full_name'      => $user->name,
                'place_of_birth' => $faker->city(),
                'date_of_birth'  => $faker->dateTimeBetween('-40 years', '-22 years')->format('Y-m-d'),
                'gender'         => $faker->randomElement(['Male', 'Female']),
                'marital_status' => $faker->randomElement(['Single', 'Married']),
                'address'        => $faker->address(),
                'phone_number'   => '08' . $faker->numerify('##########'),
                'hire_date'      => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'position_id'    => $position->id,
                'department_id'  => $department->id,
                'photo'          => null,
            ]);
        }
    }
}
