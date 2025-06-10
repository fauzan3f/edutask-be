<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamMemberController;

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

// Public routes for testing
Route::get('projects', [ProjectController::class, 'index']);
Route::get('tasks', [TaskController::class, 'index']);

// Team members route (public)
Route::get('team-members', [TeamMemberController::class, 'index']);
Route::get('team-members/{id}', [TeamMemberController::class, 'show']);

// Protected routes
Route::group(['middleware' => 'auth:api'], function () {
    // Project routes
    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects/{id}', [ProjectController::class, 'show']);
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}', [ProjectController::class, 'destroy']);
    
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
    Route::put('tasks/{id}/assign', [TaskController::class, 'assignTask']);
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