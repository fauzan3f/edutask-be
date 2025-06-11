<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json(['data' => $tasks]);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|string|in:todo,in-progress,completed',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Task created successfully', 'data' => $task], 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json(['data' => $task]);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if user is admin, project manager, or the assigned team member
        $isAdmin = $user->roles->contains('name', 'admin');
        $isProjectManager = $user->roles->contains('name', 'project_manager');
        $isAssignedMember = ($task->assigned_to == $user->id && $user->roles->contains('name', 'team_member'));

        // Debug log
        Log::info('User roles: ' . json_encode($user->roles->pluck('name')));
        Log::info('Is admin: ' . ($isAdmin ? 'true' : 'false'));
        Log::info('Is project manager: ' . ($isProjectManager ? 'true' : 'false'));
        Log::info('Is assigned member: ' . ($isAssignedMember ? 'true' : 'false'));
        Log::info('Task assigned_to: ' . $task->assigned_to);
        Log::info('User ID: ' . $user->id);

        if (!$isAdmin && !$isProjectManager && !$isAssignedMember) {
            return response()->json(['error' => 'Unauthorized. Only admin, project manager, or assigned team member can update this task.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'project_id' => 'sometimes|required|exists:projects,id',
            'status' => 'sometimes|required|string|in:todo,in-progress,completed',
            'priority' => 'sometimes|required|string|in:low,medium,high',
            'due_date' => 'sometimes|required|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update($request->all());

        return response()->json(['message' => 'Task updated successfully', 'data' => $task]);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Update the status of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if user is admin, project manager, or the assigned team member
        $isAdmin = $user->roles->contains('name', 'admin');
        $isProjectManager = $user->roles->contains('name', 'project_manager');
        $isAssignedMember = ($task->assigned_to == $user->id && $user->roles->contains('name', 'team_member'));

        if (!$isAdmin && !$isProjectManager && !$isAssignedMember) {
            return response()->json(['error' => 'Unauthorized. Only admin, project manager, or assigned team member can update task status.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:todo,in-progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Task status updated successfully', 'data' => $task]);
    }

    /**
     * Assign a task to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if user is admin or project manager
        $isAdmin = $user->roles->contains('name', 'admin');
        $isProjectManager = $user->roles->contains('name', 'project_manager');

        if (!$isAdmin && !$isProjectManager) {
            return response()->json(['error' => 'Unauthorized. Only admin or project manager can assign tasks.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->assigned_to = $request->user_id;
        $task->save();

        return response()->json(['message' => 'Task assigned successfully', 'data' => $task]);
    }
} 