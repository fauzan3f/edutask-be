<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            // Create admin user if not exists
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'department' => 'Management',
                'position' => 'Administrator'
            ]);
            
            // Assign admin role if role system is available
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('admin');
            }
        }
        
        // Get manager user
        $manager = User::where('email', 'manager@example.com')->first();
        
        if (!$manager) {
            // Create manager user if not exists
            $manager = User::create([
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'department' => 'Management',
                'position' => 'Project Manager'
            ]);
            
            // Assign project_manager role if role system is available
            if (method_exists($manager, 'assignRole')) {
                $manager->assignRole('project_manager');
            }
        }
        
        // Create sample projects
        $projects = [
            [
                'name' => 'EduTask Platform',
                'description' => 'A collaborative task management platform for educational projects',
                'status' => 'In Progress',
                'deadline' => '2025-07-30',
                'progress' => 65,
                'created_by' => $admin->id
            ],
            [
                'name' => 'Mobile Learning App',
                'description' => 'Cross-platform mobile application for e-learning',
                'status' => 'Planning',
                'deadline' => '2025-08-15',
                'progress' => 20,
                'created_by' => $manager->id
            ],
            [
                'name' => 'Virtual Lab Simulator',
                'description' => 'Interactive virtual laboratory for science experiments',
                'status' => 'Completed',
                'deadline' => '2025-06-01',
                'progress' => 100,
                'created_by' => $admin->id
            ]
        ];
        
        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Add creator as project member with manager role
            if (method_exists($project, 'members')) {
                $project->members()->attach($projectData['created_by'], ['role' => 'manager']);
                
                // Add the other user as a member
                $otherUserId = $projectData['created_by'] == $admin->id ? $manager->id : $admin->id;
                $project->members()->attach($otherUserId, ['role' => 'member']);
            }
        }
    }
} 