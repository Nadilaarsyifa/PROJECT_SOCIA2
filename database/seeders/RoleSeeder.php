<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permission yang diperlukan
        $permissions = [
            'manage users',
            'manage knowledge base',
            'verify investigations',
            'manage tickets',
            'analyze alerts',
            'manage action checklists',
            'view investigation reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Buat role Administrator
        $admin = Role::firstOrCreate([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions([
            'manage users',
            'manage knowledge base',
            'verify investigations',
        ]);

        // Buat role SOC Engineer
        $engineer = Role::firstOrCreate([
            'name' => 'SOC Engineer',
            'guard_name' => 'web',
        ]);

        $engineer->syncPermissions([
            'manage tickets',
            'analyze alerts',
            'manage action checklists',
            'view investigation reports',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
