<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $totalUsers = User::count();
    $activeUsers = User::where('status', 'active')->count();
    $inactiveUsers = User::where('status', 'inactive')->count();
    $blockedUsers = User::where('status', 'blocked')->count();

$users = User::select('id', 'name', 'email', 'role', 'status')->get()->map(function ($u) {
    return [
        'id' => $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'role' => $u->role,
        'status' => $u->status,
        'verified' => true,
        'lastSeen' => null,
        'verification_status' => $u->verification_status,
    ];
});

$usersJson = $users->toJson();
$groups = \App\Models\Group::withCount('members')->with('creator:id,name')->latest()->get()->map(function ($g) {
    return [
        'id' => $g->id,
        'name' => $g->name,
        'description' => $g->description,
        'members' => $g->members_count,
        'status' => $g->status,
        'creator' => $g->creator->name ?? 'Unknown',
    ];
});

$groupsJson = $groups->toJson();
$warnings = \App\Models\Warnings::with('user:id,name')->latest()->get()->map(function ($w) {
    return [
        'id' => $w->id,
        'user' => $w->user->name ?? 'Unknown',
        'number' => $w->warning_number,
        'reason' => $w->reason,
        'issued' => $w->issued_at,
        'expires' => $w->expires_at,
        'status' => $w->status,
    ];
});

$warningsJson = $warnings->toJson();
return view('admin', compact('totalUsers', 'activeUsers', 'inactiveUsers', 'blockedUsers', 'users', 'usersJson', 'groups', 'groupsJson', 'warnings', 'warningsJson'));
}

public function verifyLecturer(\App\Models\User $user)
{
    $user->verification_status = 'approved';
    $user->save();

    return response()->json(['success' => true]);
}
  

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrator $administrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrator $administrator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrator $administrator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrator $administrator)
    {
        //
    }
}
