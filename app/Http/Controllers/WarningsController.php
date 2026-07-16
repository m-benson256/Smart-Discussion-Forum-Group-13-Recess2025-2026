<?php

namespace App\Http\Controllers;

use App\Models\Warnings;
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
        //
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
