<?php

// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $admin      = Role::create(['name' => 'admin']);
        $teamleader = Role::create(['name' => 'teamleader']);
        $telecaller = Role::create(['name' => 'telecaller']);

        // Create admin user
        $user = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@crm.com',
            'phone'    => '9999999999',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('admin');

        // Create a team leader
        $tl = User::create([
            'name'     => 'Team Leader',
            'email'    => 'tl@crm.com',
            'phone'    => '8888888888',
            'password' => Hash::make('password'),
        ]);
        $tl->assignRole('teamleader');

        // Create a telecaller
        $tc = User::create([
            'name'     => 'Telecaller One',
            'email'    => 'tc@crm.com',
            'phone'    => '7777777777',
            'password' => Hash::make('password'),
        ]);
        $tc->assignRole('telecaller');
    }
}
