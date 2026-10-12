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

        // Daftar seluruh permission
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

        // Role Administrator: memiliki semua permission
        $admin = Role::firstOrCreate([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions($permissions);

        // Role SOC Engineer: hanya memiliki permission operasional
        $engineer = Role::firstOrCreate([
            'name' => 'SOC Engineer',
            'guard_name' => 'web',
        ]);

        $engineer->syncPermissions([
            'verify investigations',
            'manage tickets',
            'analyze alerts',
            'manage action checklists',
            'view investigation reports',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
