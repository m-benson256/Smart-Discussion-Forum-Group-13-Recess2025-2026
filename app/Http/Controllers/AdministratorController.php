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
        'verification_status' =>'approved',
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
return view('admin', compact('totalUsers', 'activeUsers', 'inactiveUsers', 'blockedUsers', 'users', 'usersJson', 'groups', 'groupsJson'));
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
