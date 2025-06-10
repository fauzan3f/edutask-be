<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use Carbon\Carbon;

class ProjectsAndTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $admin = User::where('email', 'admin@edutask.com')->first();
        $manager = User::where('email', 'manager@edutask.com')->first();
        $member = User::where('email', 'member@edutask.com')->first();
        $fauzan = User::where('email', 'fauzan@edutask.com')->first();
        $rizki = User::where('email', 'rizki@edutask.com')->first();
        $sarah = User::where('email', 'sarah@edutask.com')->first();
        $budi = User::where('email', 'budi@edutask.com')->first();

        // Create EduTask Project Management System project
        $eduTaskProject = Project::create([
            'name' => 'EduTask Project Management System',
            'description' => 'A web-based project management system for academic tasks with JWT authentication and interactive frontend using TypeScript.',
            'created_by' => $admin->id,
            'manager_id' => $manager->id,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(60),
            'status' => 'in_progress',
            'priority' => 'high',
            'code' => 'EDUTASK-001',
            'is_archived' => false,
        ]);

        // Add team members to the project
        $eduTaskProject->members()->attach([
            $fauzan->id => ['role' => 'member'],
            $rizki->id => ['role' => 'member'],
            $sarah->id => ['role' => 'member'],
            $budi->id => ['role' => 'member'],
            $member->id => ['role' => 'member'],
        ]);

        // Create tasks for EduTask project
        $tasks = [
            [
                'title' => 'Database Design and Implementation',
                'description' => 'Design and implement the database schema for the EduTask system.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'done',
                'priority' => 'high',
                'due_date' => Carbon::now()->subDays(20),
                'start_date' => Carbon::now()->subDays(25),
                'estimated_hours' => 15,
                'actual_hours' => 18,
                'completed_at' => Carbon::now()->subDays(18),
                'task_code' => 'EDUTASK-001-T1',
            ],
            [
                'title' => 'JWT Authentication Implementation',
                'description' => 'Implement JWT token authentication with refresh token functionality.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'done',
                'priority' => 'high',
                'due_date' => Carbon::now()->subDays(15),
                'start_date' => Carbon::now()->subDays(18),
                'estimated_hours' => 10,
                'actual_hours' => 12,
                'completed_at' => Carbon::now()->subDays(14),
                'task_code' => 'EDUTASK-001-T2',
            ],
            [
                'title' => 'Frontend Setup with Vue and TypeScript',
                'description' => 'Set up the frontend project with Vue.js and TypeScript.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $fauzan->id,
                'created_by' => $manager->id,
                'status' => 'done',
                'priority' => 'high',
                'due_date' => Carbon::now()->subDays(15),
                'start_date' => Carbon::now()->subDays(18),
                'estimated_hours' => 8,
                'actual_hours' => 7,
                'completed_at' => Carbon::now()->subDays(14),
                'task_code' => 'EDUTASK-001-T3',
            ],
            [
                'title' => 'User Interface Design',
                'description' => 'Design the user interface for the EduTask system using Tailwind CSS.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $sarah->id,
                'created_by' => $manager->id,
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => Carbon::now()->subDays(10),
                'start_date' => Carbon::now()->subDays(15),
                'estimated_hours' => 20,
                'actual_hours' => 22,
                'completed_at' => Carbon::now()->subDays(8),
                'task_code' => 'EDUTASK-001-T4',
            ],
            [
                'title' => 'Project Management Features',
                'description' => 'Implement CRUD operations for projects and tasks.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(5),
                'start_date' => Carbon::now()->subDays(5),
                'estimated_hours' => 25,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T5',
            ],
            [
                'title' => 'Task Assignment and Status Management',
                'description' => 'Implement task assignment and status management features.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(7),
                'start_date' => Carbon::now()->subDays(3),
                'estimated_hours' => 15,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T6',
            ],
            [
                'title' => 'Dashboard Implementation',
                'description' => 'Create the dashboard with statistics and charts using Chart.js.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $fauzan->id,
                'created_by' => $manager->id,
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(10),
                'start_date' => Carbon::now()->subDays(2),
                'estimated_hours' => 18,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T7',
            ],
            [
                'title' => 'File Upload System',
                'description' => 'Implement file upload functionality for tasks and projects.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $budi->id,
                'created_by' => $manager->id,
                'status' => 'to_do',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(15),
                'start_date' => null,
                'estimated_hours' => 12,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T8',
            ],
            [
                'title' => 'Presentation Scheduling System',
                'description' => 'Implement the presentation scheduling system with notifications.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $member->id,
                'created_by' => $manager->id,
                'status' => 'to_do',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(20),
                'start_date' => null,
                'estimated_hours' => 15,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T9',
            ],
            [
                'title' => 'Testing and Bug Fixing',
                'description' => 'Test the application and fix any bugs found.',
                'project_id' => $eduTaskProject->id,
                'assigned_to' => $budi->id,
                'created_by' => $manager->id,
                'status' => 'to_do',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(25),
                'start_date' => null,
                'estimated_hours' => 20,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'EDUTASK-001-T10',
            ],
        ];

        // Create tasks
        foreach ($tasks as $taskData) {
            $task = Task::create($taskData);
            
            // Add some comments to completed tasks
            if ($task->status === 'done') {
                Comment::create([
                    'content' => 'I have completed this task according to the requirements.',
                    'task_id' => $task->id,
                    'user_id' => $task->assigned_to,
                    'created_at' => $task->completed_at,
                ]);
                
                Comment::create([
                    'content' => 'Great job! The implementation looks good.',
                    'task_id' => $task->id,
                    'user_id' => $manager->id,
                    'created_at' => Carbon::parse($task->completed_at)->addHours(2),
                ]);
            }
            
            // Add comments to in-progress tasks
            if ($task->status === 'in_progress') {
                Comment::create([
                    'content' => 'I am currently working on this task. Will update soon.',
                    'task_id' => $task->id,
                    'user_id' => $task->assigned_to,
                    'created_at' => $task->start_date,
                ]);
                
                Comment::create([
                    'content' => 'How is the progress going? Any blockers?',
                    'task_id' => $task->id,
                    'user_id' => $manager->id,
                    'created_at' => Carbon::parse($task->start_date)->addDays(1),
                ]);
                
                Comment::create([
                    'content' => 'Making good progress. No blockers at the moment.',
                    'task_id' => $task->id,
                    'user_id' => $task->assigned_to,
                    'created_at' => Carbon::parse($task->start_date)->addDays(1)->addHours(2),
                ]);
            }
        }

        // Create a Research Project
        $researchProject = Project::create([
            'name' => 'Academic Research on Machine Learning',
            'description' => 'Research project focused on applying machine learning algorithms to academic data.',
            'created_by' => $admin->id,
            'manager_id' => $manager->id,
            'start_date' => Carbon::now()->subDays(15),
            'end_date' => Carbon::now()->addDays(90),
            'status' => 'in_progress',
            'priority' => 'medium',
            'code' => 'RESEARCH-001',
            'is_archived' => false,
        ]);

        // Add team members to the research project
        $researchProject->members()->attach([
            $rizki->id => ['role' => 'member'],
            $sarah->id => ['role' => 'member'],
            $budi->id => ['role' => 'member'],
        ]);

        // Create tasks for Research project
        $researchTasks = [
            [
                'title' => 'Literature Review',
                'description' => 'Review existing literature on machine learning applications in academia.',
                'project_id' => $researchProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'done',
                'priority' => 'high',
                'due_date' => Carbon::now()->subDays(5),
                'start_date' => Carbon::now()->subDays(15),
                'estimated_hours' => 20,
                'actual_hours' => 25,
                'completed_at' => Carbon::now()->subDays(3),
                'task_code' => 'RESEARCH-001-T1',
            ],
            [
                'title' => 'Data Collection',
                'description' => 'Collect academic data for machine learning training.',
                'project_id' => $researchProject->id,
                'assigned_to' => $sarah->id,
                'created_by' => $manager->id,
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(10),
                'start_date' => Carbon::now()->subDays(5),
                'estimated_hours' => 30,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'RESEARCH-001-T2',
            ],
            [
                'title' => 'Algorithm Implementation',
                'description' => 'Implement machine learning algorithms for data analysis.',
                'project_id' => $researchProject->id,
                'assigned_to' => $rizki->id,
                'created_by' => $manager->id,
                'status' => 'to_do',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(20),
                'start_date' => null,
                'estimated_hours' => 40,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'RESEARCH-001-T3',
            ],
            [
                'title' => 'Research Paper Draft',
                'description' => 'Write the first draft of the research paper.',
                'project_id' => $researchProject->id,
                'assigned_to' => $budi->id,
                'created_by' => $manager->id,
                'status' => 'to_do',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(40),
                'start_date' => null,
                'estimated_hours' => 35,
                'actual_hours' => null,
                'completed_at' => null,
                'task_code' => 'RESEARCH-001-T4',
            ],
        ];

        // Create research tasks
        foreach ($researchTasks as $taskData) {
            $task = Task::create($taskData);
            
            // Add some comments to completed tasks
            if ($task->status === 'done') {
                Comment::create([
                    'content' => 'Literature review completed. Found several interesting papers on this topic.',
                    'task_id' => $task->id,
                    'user_id' => $task->assigned_to,
                    'created_at' => $task->completed_at,
                ]);
                
                Comment::create([
                    'content' => 'Excellent work! This will provide a good foundation for our research.',
                    'task_id' => $task->id,
                    'user_id' => $manager->id,
                    'created_at' => Carbon::parse($task->completed_at)->addHours(3),
                ]);
            }
        }
    }
}
