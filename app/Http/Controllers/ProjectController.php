<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:Planning,In Progress,Completed',
            'deadline' => 'sometimes|required|date',
            'progress' => 'sometimes|required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project->update($request->all());

        return response()->json(['message' => 'Project updated successfully', 'data' => $project]);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
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