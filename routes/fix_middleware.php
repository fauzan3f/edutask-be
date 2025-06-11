<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Hapus routes lama dan tambahkan yang baru dengan middleware yang benar
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {
    // Project routes
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    
    // Uncomment salah satu opsi berikut sesuai dengan yang tersedia di aplikasi
    
    // Opsi 1: Gunakan middleware manual
    // Route::put('projects/{id}', function($id, \Illuminate\Http\Request $request) {
    //     $user = auth()->user();
    //     if (!$user->hasRole('admin') && !$user->hasRole('project_manager')) {
    //         return response()->json(['error' => 'Unauthorized. Only admin and project manager can update projects.'], 403);
    //     }
    //     return app(ProjectController::class)->update($request, $id);
    // });
    
    // Opsi 2: Gunakan role_or_permission middleware
    // Route::put('projects/{id}', [ProjectController::class, 'update'])
    //     ->middleware('role_or_permission:admin|project_manager|update projects');
}); 