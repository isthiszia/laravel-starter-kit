<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::updateOrCreate(
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
            ]
        );

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        $admin = Role::updateOrCreate(
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ]
        );

        $admin->syncPermissions([
            'dashboard',
        ]);
    }
}
