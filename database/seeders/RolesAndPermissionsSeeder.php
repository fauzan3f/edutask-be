<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'manage users',
            
            // Project management
            'create project',
            'update project',
            'delete project',
            'view project',
            
            // Task management
            'create task',
            'update task',
            'delete task',
            'view task',
            'assign tasks',
            'update tasks',
            
            // Comment management
            'comment tasks',
            
            // Dashboard
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage users',
            'create project',
            'update project',
            'delete project',
            'view project',
            'create task',
            'update task',
            'delete task',
            'view task',
            'assign tasks',
            'update tasks',
            'comment tasks',
            'view dashboard',
        ]);

        // Project Manager role
        $projectManagerRole = Role::create(['name' => 'project_manager']);
        $projectManagerRole->givePermissionTo([
            'create project',
            'update project',
            'view project',
            'create task',
            'update task',
            'delete task',
            'view task',
            'assign tasks',
            'update tasks',
            'comment tasks',
            'view dashboard',
        ]);

        // Team Member role
        $teamMemberRole = Role::create(['name' => 'team_member']);
        $teamMemberRole->givePermissionTo([
            'view project',
            'view task',
            'update tasks',
            'comment tasks',
            'view dashboard',
        ]);

        // Create default admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@edutask.com',
            'password' => Hash::make('password'),
            'position' => 'System Administrator',
            'department' => 'IT',
        ]);
        $admin->assignRole('admin');

        // Create default project manager
        $manager = User::create([
            'name' => 'Project Manager',
            'email' => 'manager@edutask.com',
            'password' => Hash::make('password'),
            'position' => 'Project Manager',
            'department' => 'Project Management',
        ]);
        $manager->assignRole('project_manager');

        // Create default team member
        $member = User::create([
            'name' => 'Team Member',
            'email' => 'member@edutask.com',
            'password' => Hash::make('password'),
            'position' => 'Developer',
            'department' => 'Development',
        ]);
        $member->assignRole('team_member');

        // Create four more team members (representing the group)
        $teamMembers = [
            [
                'name' => 'Fauzan Fathurrahman',
                'email' => 'fauzan@edutask.com',
                'password' => Hash::make('password'),
                'position' => 'Frontend Developer',
                'department' => 'Development',
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'rizki@edutask.com',
                'password' => Hash::make('password'),
                'position' => 'Backend Developer',
                'department' => 'Development',
            ],
            [
                'name' => 'Sarah Kamila',
                'email' => 'sarah@edutask.com',
                'password' => Hash::make('password'),
                'position' => 'UI/UX Designer',
                'department' => 'Design',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@edutask.com',
                'password' => Hash::make('password'),
                'position' => 'QA Engineer',
                'department' => 'Quality Assurance',
            ],
        ];

        foreach ($teamMembers as $teamMember) {
            $user = User::create($teamMember);
            $user->assignRole('team_member');
        }
    }
}
