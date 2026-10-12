<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat atau memperbarui akun Administrator
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => 'GantiPasswordAdmin123!',
                'role' => 'Administrator',
            ]
        );

        // Membuat atau memperbarui akun SOC Engineer
        $engineer = User::updateOrCreate(
            ['username' => 'socengineer'],
            [
                'nama' => 'SOC Engineer',
                'password' => 'GantiPasswordEngineer123!',
                'role' => 'SOC Engineer',
            ]
        );

        // Menghubungkan akun dengan role Spatie
        $admin->syncRoles(['Administrator']);
        $engineer->syncRoles(['SOC Engineer']);
    }
}
