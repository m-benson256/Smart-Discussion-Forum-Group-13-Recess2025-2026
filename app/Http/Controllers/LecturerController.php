<?php

namespace App\Http\Controllers;

use App\Models\Announcements;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturerName = auth()->user()?->name ?? 'Lecturer';
        $totalStudents = User::where('role', 'student')->count();
        $activeDiscussions = Topic::count();

        $announcements = Announcements::with('user', 'quiz')
            ->latest()
            ->get();

        return view('lecturer.dashboard', [
            'lecturerName' => $lecturerName,
            'announcements' => $announcements,
            'totalStudents' => $totalStudents,
            'activeDiscussions' => $activeDiscussions,
        ]); // Loads resources/views/lecturer/dashboard.blade.php
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
    public function show(Lecturer $lecturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer)
    {
        //
    }
}
