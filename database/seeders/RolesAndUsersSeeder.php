<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndUsersSeeder extends Seeder
{

    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Role::firstOrCreate(['name' => 'Admin']);
        // Role::firstOrCreate(['name' => 'User']);
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'sanctum']);
        Role::firstOrCreate(['name' => 'User', 'guard_name' => 'sanctum']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('p@ssw0rd.123'),
                'email_verified_at' => now(),
                'country' => 'Saudi Arabia',
                'city' => 'Riyadh',
            ]
        );
        $admin->assignRole('Admin');


        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'first_name' => 'Regular',
                'last_name' => 'User',
                'password' => Hash::make('p@ssw0rd.123'),
                'email_verified_at' => now(),
                'country' => 'Saudi Arabia',
                'city' => 'Jeddah',
            ]
        );
        $user->assignRole('User');
    }
}