<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $business = Business::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name' => 'Super Admin',
                'phone' => '',
                'address' => 'Karachi, Pakistan',
                'is_active' => true,
            ]
        );

        $user = User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'business_id' => $business->id,
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('superadmin12345'),
                'remember_token' => null,
            ]
        );

        $user->syncRoles([
            'super-admin',
        ]);
    }
}
