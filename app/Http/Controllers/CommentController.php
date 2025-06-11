<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for a task.
     *
     * @param  int  $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($taskId)
    {
        $task = Task::findOrFail($taskId);
        $comments = $task->comments()->with('user')->get();
        
        return response()->json(['data' => $comments]);
    }

    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $taskId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::findOrFail($taskId);
        
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->task_id = $taskId;
        $comment->save();
        
        // Load the user relationship
        $comment->load('user');
        
        return response()->json(['message' => 'Comment added successfully', 'data' => $comment], 201);
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = Comment::findOrFail($id);
        
        // Only the comment author can update it
        $user = Auth::user();
        $isAdminOrManager = $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'project_manager');
        if ($comment->user_id !== Auth::id() && !$isAdminOrManager) {
            return response()->json(['error' => 'Unauthorized. You can only edit your own comments.'], 403);
        }
        
        $comment->content = $request->content;
        $comment->save();
        
        return response()->json(['message' => 'Comment updated successfully', 'data' => $comment]);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Only the comment author or admins/project managers can delete it
        $user = Auth::user();
        $isAdminOrManager = $user->roles->contains('name', 'admin') || $user->roles->contains('name', 'project_manager');
        if ($comment->user_id !== Auth::id() && !$isAdminOrManager) {
            return response()->json(['error' => 'Unauthorized. You can only delete your own comments.'], 403);
        }
        
        $comment->delete();
        
        return response()->json(['message' => 'Comment deleted successfully']);
    }
} 