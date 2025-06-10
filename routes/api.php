<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

/* 
// Protected routes - Commented out until controllers are created
Route::group(['middleware' => 'auth:api'], function () {
    // User routes
    Route::get('users', 'App\Http\Controllers\UserController@index')->middleware('permission:manage users');
    Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
    Route::put('users/{id}', 'App\Http\Controllers\UserController@update');
    
    // Project routes
    Route::get('projects', 'App\Http\Controllers\ProjectController@index');
    Route::post('projects', 'App\Http\Controllers\ProjectController@store')->middleware('permission:create project');
    Route::get('projects/{id}', 'App\Http\Controllers\ProjectController@show')->middleware('permission:view project');
    Route::put('projects/{id}', 'App\Http\Controllers\ProjectController@update')->middleware('permission:update project');
    Route::delete('projects/{id}', 'App\Http\Controllers\ProjectController@destroy')->middleware('permission:delete project');
    
    // Project members
    Route::get('projects/{id}/members', 'App\Http\Controllers\ProjectController@members');
    Route::post('projects/{id}/members', 'App\Http\Controllers\ProjectController@addMember')->middleware('permission:update project');
    Route::delete('projects/{id}/members/{userId}', 'App\Http\Controllers\ProjectController@removeMember')->middleware('permission:update project');
    
    // Task routes
    Route::get('tasks', 'App\Http\Controllers\TaskController@index');
    Route::post('tasks', 'App\Http\Controllers\TaskController@store')->middleware('permission:create task');
    Route::get('tasks/{id}', 'App\Http\Controllers\TaskController@show')->middleware('permission:view task');
    Route::put('tasks/{id}', 'App\Http\Controllers\TaskController@update')->middleware('permission:update task');
    Route::delete('tasks/{id}', 'App\Http\Controllers\TaskController@destroy')->middleware('permission:delete task');
    
    // Task status
    Route::put('tasks/{id}/status', 'App\Http\Controllers\TaskController@updateStatus')->middleware('permission:update tasks');
    
    // Task assignment
    Route::put('tasks/{id}/assign', 'App\Http\Controllers\TaskController@assignTask')->middleware('permission:assign tasks');
    
    // Project tasks
    Route::get('projects/{id}/tasks', 'App\Http\Controllers\ProjectController@tasks');
    
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