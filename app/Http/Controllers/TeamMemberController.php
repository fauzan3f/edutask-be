<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the team members.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $members = TeamMember::all();
        return response()->json(['data' => $members]);
    }

    /**
     * Display the specified team member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $member = TeamMember::findOrFail($id);
        return response()->json(['data' => $member]);
    }
} 