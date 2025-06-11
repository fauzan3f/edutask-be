<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = Project::all();
        return response()->json(['data' => $projects]);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Planning,In Progress,Completed',
            'deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'deadline' => $request->deadline,
            'progress' => $request->progress ?? 0,
            'created_by' => Auth::id(),
        ]);

        // Add the creator as a project member with manager role
        if (method_exists($project, 'members')) {
            $project->members()->attach(Auth::id(), ['role' => 'manager']);
        }

        return response()->json(['message' => 'Project created successfully', 'data' => $project], 201);
    }

    /**
     * Display the specified project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json(['data' => $project]);
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Debug log
            Log::info('Update project request data:', $request->all());
            Log::info('Project ID: ' . $id);
            Log::info('Progress type: ' . gettype($request->progress));
            Log::info('Progress value: ' . $request->progress);
            
            // Check if user is admin or project manager
            $user = Auth::user();
            Log::info('User roles:', $user->roles->pluck('name')->toArray());
            
            // Manual authorization check
            if (!($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'project_manager'))) {
                return response()->json(['error' => 'Unauthorized. Only admin and project manager can update projects.'], 403);
            }

            $project = Project::findOrFail($id);
            Log::info('Project found:', $project->toArray());

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'status' => 'sometimes|required|string|in:Planning,In Progress,Completed',
                'deadline' => 'sometimes|required|date',
                'progress' => 'sometimes|required|integer|min:0|max:100',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Convert progress to integer if it exists
            $data = $request->all();
            if (isset($data['progress'])) {
                $data['progress'] = (int) $data['progress'];
                Log::info('Progress after conversion: ' . $data['progress']);
            }

            // Update project with specific handling for progress
            $project->fill($data);
            if (isset($data['progress'])) {
                $project->progress = (int) $data['progress'];
                Log::info('Progress set directly: ' . $project->progress);
            }
            $project->save();
            
            Log::info('Project updated successfully');
            Log::info('Updated project data:', $project->toArray());

            return response()->json(['message' => 'Project updated successfully', 'data' => $project]);
        } catch (\Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Failed to update project: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user->roles->contains('name', 'admin')) {
            return response()->json(['error' => 'Unauthorized. Only admin can delete projects.'], 403);
        }

        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }

    /**
     * Get all members of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function members($id)
    {
        $project = Project::findOrFail($id);
        
        if (method_exists($project, 'members')) {
            $members = $project->members;
            return response()->json(['data' => $members]);
        }
        
        return response()->json(['data' => []]);
    }

    /**
     * Add a member to a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:manager,member,viewer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project = Project::findOrFail($id);
        
        if (method_exists($project, 'members')) {
            $project->members()->attach($request->user_id, ['role' => $request->role]);
            return response()->json(['message' => 'Member added successfully']);
        }
        
        return response()->json(['message' => 'Member relationship not available'], 422);
    }

    /**
     * Remove a member from a project.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMember($id, $userId)
    {
        $project = Project::findOrFail($id);
        
        if (method_exists($project, 'members')) {
            $project->members()->detach($userId);
            return response()->json(['message' => 'Member removed successfully']);
        }
        
        return response()->json(['message' => 'Member relationship not available'], 422);
    }

    /**
     * Get all tasks of a project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function tasks($id)
    {
        $project = Project::findOrFail($id);
        
        if (method_exists($project, 'tasks')) {
            $tasks = $project->tasks;
            return response()->json(['data' => $tasks]);
        }
        
        return response()->json(['data' => []]);
    }
} 