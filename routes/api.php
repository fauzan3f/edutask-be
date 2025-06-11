<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Simple test route for debugging
Route::get('/test', [AuthController::class, 'test']);

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Test route for debugging
Route::get('test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Debug route for projects
Route::get('debug/projects/{id}', function($id) {
    $project = \App\Models\Project::find($id);
    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }
    
    return response()->json([
        'project' => $project,
        'progress_type' => gettype($project->progress),
        'progress_value' => $project->progress,
        'raw_progress' => \Illuminate\Support\Facades\DB::select('SELECT progress FROM projects WHERE id = ?', [$id])
    ]);
});

// Debug route to update project progress
Route::get('debug/projects/{id}/update-progress/{value}', function($id, $value) {
    try {
        $project = \App\Models\Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        
        // Update progress directly
        \Illuminate\Support\Facades\DB::update('UPDATE projects SET progress = ? WHERE id = ?', [(int)$value, $id]);
        
        // Reload the project
        $project = \App\Models\Project::find($id);
        
        return response()->json([
            'message' => 'Progress updated successfully',
            'project' => $project,
            'progress_type' => gettype($project->progress),
            'progress_value' => $project->progress
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Debug route to update project using model
Route::get('debug/projects/{id}/update-model/{value}', function($id, $value) {
    try {
        $project = \App\Models\Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        
        // Log before update
        \Illuminate\Support\Facades\Log::info('Before update:', [
            'progress_type' => gettype($project->progress),
            'progress_value' => $project->progress
        ]);
        
        // Update using model
        $project->progress = $value;
        $project->save();
        
        // Log after update
        \Illuminate\Support\Facades\Log::info('After update:', [
            'progress_type' => gettype($project->progress),
            'progress_value' => $project->progress
        ]);
        
        return response()->json([
            'message' => 'Project updated successfully using model',
            'project' => $project,
            'progress_type' => gettype($project->progress),
            'progress_value' => $project->progress
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Public routes for testing
Route::get('projects', [ProjectController::class, 'index']);
Route::get('tasks', [TaskController::class, 'index']);

// Team members route (public)
Route::get('team-members', [TeamMemberController::class, 'index']);
Route::get('team-members/{id}', [TeamMemberController::class, 'show']);

// Protected routes
Route::group(['middleware' => 'auth:api'], function () {
    // User routes
    Route::get('users', [UserController::class, 'index'])->middleware('permission:manage users');
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('permission:manage users');
    
    // Project routes
    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects/{id}', [ProjectController::class, 'show']);
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}', [ProjectController::class, 'destroy'])->middleware('role:admin');
    
    // Project members
    Route::get('projects/{id}/members', [ProjectController::class, 'members']);
    Route::post('projects/{id}/members', [ProjectController::class, 'addMember']);
    Route::delete('projects/{id}/members/{userId}', [ProjectController::class, 'removeMember']);
    
    // Project tasks
    Route::get('projects/{id}/tasks', [ProjectController::class, 'tasks']);
    
    // Task routes
    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::put('tasks/{id}', [TaskController::class, 'update']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
    
    // Task status
    Route::put('tasks/{id}/status', [TaskController::class, 'updateStatus']);
    
    // Task assignment
    Route::put('tasks/{id}/assign', [TaskController::class, 'assignTask'])->middleware('role:admin|project_manager');

    // Comments
    Route::get('tasks/{id}/comments', 'App\Http\Controllers\CommentController@index');
    Route::post('tasks/{id}/comments', 'App\Http\Controllers\CommentController@store');
    Route::put('comments/{id}', 'App\Http\Controllers\CommentController@update');
    Route::delete('comments/{id}', 'App\Http\Controllers\CommentController@destroy');
});

// Get users for task assignment (no permission required)
Route::get('task-assignees', function() {
    // Get all users with their roles
    $users = \App\Models\User::with('roles')->select('id', 'name', 'email', 'position', 'department')->get();
    
    // Format the response to include role information
    $users->each(function($user) {
        $user->role = $user->roles->pluck('name')->first() ?: 'User';
    });
    
    return response()->json(['data' => $users]);
});

/* 
// Protected routes - Commented out until controllers are created
Route::group(['middleware' => 'auth:api'], function () {
    // User routes
    Route::get('users', 'App\Http\Controllers\UserController@index')->middleware('permission:manage users');
    Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('users/{id}', 'App\Http\Controllers\UserController@update');
    
    // Comments
    Route::get('tasks/{id}/comments', 'App\Http\Controllers\CommentController@index');
    Route::post('tasks/{id}/comments', 'App\Http\Controllers\CommentController@store')->middleware('permission:comment tasks');
    Route::put('comments/{id}', 'App\Http\Controllers\CommentController@update');
    Route::delete('comments/{id}', 'App\Http\Controllers\CommentController@destroy');
    
    // Dashboard
    Route::get('dashboard', 'App\Http\Controllers\DashboardController@index')->middleware('permission:view dashboard');
    
    // Presentations
    Route::get('presentations', 'App\Http\Controllers\PresentationController@index');
    Route::post('presentations', 'App\Http\Controllers\PresentationController@store');
    Route::get('presentations/{id}', 'App\Http\Controllers\PresentationController@show');
    Route::put('presentations/{id}', 'App\Http\Controllers\PresentationController@update');
    Route::delete('presentations/{id}', 'App\Http\Controllers\PresentationController@destroy');
    
    // Presentation attendees
    Route::get('presentations/{id}/attendees', 'App\Http\Controllers\PresentationController@attendees');
    Route::post('presentations/{id}/attendees', 'App\Http\Controllers\PresentationController@addAttendee');
    Route::put('presentations/{id}/attendees/{userId}', 'App\Http\Controllers\PresentationController@updateAttendance');
    
    // File uploads
    Route::post('upload/task/{id}', 'App\Http\Controllers\FileUploadController@uploadTaskFile');
    Route::post('upload/project/{id}', 'App\Http\Controllers\FileUploadController@uploadProjectFile');
    Route::get('files/task/{id}', 'App\Http\Controllers\FileUploadController@getTaskFiles');
    Route::get('files/project/{id}', 'App\Http\Controllers\FileUploadController@getProjectFiles');
    Route::delete('files/{id}', 'App\Http\Controllers\FileUploadController@destroy');
});
*/ 