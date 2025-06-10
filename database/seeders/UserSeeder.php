<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'department' => 'IT',
            'position' => 'System Administrator',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create project manager user
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'department' => 'Management',
            'position' => 'Project Manager',
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('project_manager');

        // Create team member user
        $member = User::create([
            'name' => 'Team Member',
            'email' => 'member@example.com',
            'password' => Hash::make('password'),
            'department' => 'Development',
            'position' => 'Developer',
            'email_verified_at' => now(),
        ]);
        $member->assignRole('team_member');
    }
} 