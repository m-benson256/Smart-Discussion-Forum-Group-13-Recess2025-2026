<?php

namespace App\Http\Controllers;

use App\Models\User_interests;
use Illuminate\Http\Request;

class UserInterestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        

    return response()->json(
        \App\Models\User_interests::orderBy('InterestName')->get(['InterestID', 'InterestName'])
    );

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
    public function show(User_interests $user_interests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User_interests $user_interests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User_interests $user_interests)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User_interests $user_interests)
    {
        //
    }
}
