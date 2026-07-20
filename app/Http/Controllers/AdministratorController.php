<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Warnings;
use App\Models\Group;

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

$users = User::select('id', 'name', 'email', 'role', 'status','verification_status')->get()->map(function ($u) {
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
return view('admin.admin', compact('totalUsers', 'activeUsers', 'inactiveUsers', 'blockedUsers', 'users', 'usersJson', 'groups', 'groupsJson', 'warnings', 'warningsJson'));
}

public function verifyLecturer(\App\Models\User $user)
{
    $user->verification_status = 'approved';
    $user->save();

    return response()->json(['success' => true]);
}
public function rejectLecturer(\App\Models\User $user)
{
    $user->verification_status = 'rejected';
    $user->status = 'blocked';
    $user->save();

    return response()->json(['success' => true]);
}

public function storeWarning(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'reason' => 'required|string',
        'warning_number' => 'required|integer|min:1|max:3',
    ]);

    $warnings = Warnings::create([
        'user_id' => $request->user_id,
        'reason' => $request->reason,
        'warning_number' => $request->warning_number,
        'issued_at' => now(),
        'status' => 'active',
    ]);

    return response()->json(['success' => true, 'warning' => $warnings]);
}
public function toggleGroupStatus($id)
{
    $group = Group::findOrFail($id);
    $group->status = $group->status === 'blocked' ? 'active' : 'blocked';
    $group->save();

    return response()->json(['success' => true, 'status' => $group->status]);
}
  
public function blockUser($id)
{
    $user = User::findOrFail($id);
    $user->status = 'blocked';
    $user->save();

    return response()->json(['success' => true, 'status' => $user->status]);
}

public function unblockUser($id)
{
    $user = User::findOrFail($id);
    $user->status = 'active';
    $user->save();

    return response()->json(['success' => true, 'status' => $user->status]);
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
