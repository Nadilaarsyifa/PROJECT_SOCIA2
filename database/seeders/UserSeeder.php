<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => 'GantiPasswordAdmin123!',
                'role' => 'Administrator',
            ]
        );

        User::updateOrCreate(
            ['username' => 'socengineer'],
            [
                'nama' => 'SOC Engineer',
                'password' => 'GantiPasswordEngineer123!',
                'role' => 'SOC Engineer',
            ]
        );
    }
}