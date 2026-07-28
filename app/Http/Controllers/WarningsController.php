<?php

namespace App\Http\Controllers;

use App\Models\Warnings\Announcement;
use Illuminate\Http\Request;

class WarningsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
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

    return $warnings;
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
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'warning_number' => 'required|integer',
            'reason' => 'required|string',
            'issued_at' => 'required|date',
            'expires_at' => 'nullable|date',
            'status' => 'required|string',
        ]);

        Warnings::create($validatedData)([
            'user_id' => $request->input('user_id'),
            'warning_number' => $request->input('warning_number'),
            'reason' => $request->input('reason'),
            'issued_at' => $request->input('issued_at'),
            'expires_at' => $request->input('expires_at'),
            'status' => $request->input('status'),
        ]);
        //CREATE THE LINKED ANNOUNCEMENT HERE
        Announcement::create([
            'title' => 'Warning Issued',
            'content' => 'A warning has been issued to user ID: ' . $request->input('user_id') . '. Reason: ' . $request->input('reason'),
            'user_id' => $request->input('user_id'),
        ]);
        return response()->json(['message' => 'Warning created successfully'], 201);




        
    }

    /**
     * Display the specified resource.
     */
    public function show(Warnings $warnings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warnings $warnings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warnings $warnings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warnings $warnings)
    {
        //
    }
}
