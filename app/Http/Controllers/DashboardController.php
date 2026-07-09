<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Topic;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all groups from the database
        $groups = Group::all();

        // Fetch topics and eager-load the user relationship to display names cleanly
        $topics = Topic::with('user')->latest()->get();

        // Return your custom student dashboard blade file
        return view('student.dashboard', compact('groups', 'topics'));
    }
}
