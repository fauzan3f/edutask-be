<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $projectManager = Role::create(['name' => 'project_manager', 'guard_name' => 'api']);
        $teamMember = Role::create(['name' => 'team_member', 'guard_name' => 'api']);

        // Create permissions
        // Projects
        $viewAnyProject = Permission::create(['name' => 'view any project', 'guard_name' => 'api']);
        $viewProject = Permission::create(['name' => 'view project', 'guard_name' => 'api']);
        $createProject = Permission::create(['name' => 'create project', 'guard_name' => 'api']);
        $updateProject = Permission::create(['name' => 'update project', 'guard_name' => 'api']);
        $deleteProject = Permission::create(['name' => 'delete project', 'guard_name' => 'api']);
        
        // Tasks
        $viewAnyTask = Permission::create(['name' => 'view any task', 'guard_name' => 'api']);
        $viewTask = Permission::create(['name' => 'view task', 'guard_name' => 'api']);
        $createTask = Permission::create(['name' => 'create task', 'guard_name' => 'api']);
        $updateTask = Permission::create(['name' => 'update task', 'guard_name' => 'api']);
        $deleteTask = Permission::create(['name' => 'delete task', 'guard_name' => 'api']);
        
        // Users
        $viewAnyUser = Permission::create(['name' => 'view any user', 'guard_name' => 'api']);
        $viewUser = Permission::create(['name' => 'view user', 'guard_name' => 'api']);
        $createUser = Permission::create(['name' => 'create user', 'guard_name' => 'api']);
        $updateUser = Permission::create(['name' => 'update user', 'guard_name' => 'api']);
        $deleteUser = Permission::create(['name' => 'delete user', 'guard_name' => 'api']);

        // Comments
        $viewComment = Permission::create(['name' => 'view comment', 'guard_name' => 'api']);
        $createComment = Permission::create(['name' => 'create comment', 'guard_name' => 'api']);
        $updateComment = Permission::create(['name' => 'update comment', 'guard_name' => 'api']);
        $deleteComment = Permission::create(['name' => 'delete comment', 'guard_name' => 'api']);

        // Assign permissions to roles
        // Admin has all permissions
        $admin->givePermissionTo(Permission::all());

        // Project Manager permissions
        $projectManager->givePermissionTo([
            $viewAnyProject, $viewProject, $createProject, $updateProject,
            $viewAnyTask, $viewTask, $createTask, $updateTask, $deleteTask,
            $viewAnyUser, $viewUser,
            $viewComment, $createComment, $updateComment, $deleteComment
        ]);

        // Team Member permissions
        $teamMember->givePermissionTo([
            $viewProject,
            $viewTask, $updateTask,
            $viewUser,
            $viewComment, $createComment, $updateComment
        ]);
    }
} 