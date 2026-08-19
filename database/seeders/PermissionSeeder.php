<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'dashboard'],
            ['name' => 'subscription'],
            ['name' => 'delete-subscription'],
            ['name' => 'account'],
            ['name' => 'users'],
            ['name' => 'add-user'],
            ['name' => 'edit-user'],
            ['name' => 'delete-user'],
            ['name' => 'business'],
            ['name' => 'add-business'],
            ['name' => 'edit-business'],
            ['name' => 'delete-business'],
            ['name' => 'access'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ]
            );
        }
    }
}
