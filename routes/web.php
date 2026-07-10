<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AdministratorController;

// 1. Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Main Auth Traffic Controller (Handles redirecting /dashboard based on email domain)
Route::get('/dashboard', function () {
   $user = auth()->user();
    
   // 0. If it's an admin, send them to the admin route
if ($user && $user->role === 'admin') {
    return redirect()->route('admin.dashboard');
}

// 1. If it's a lecturer, send them to the lecturer route
if ($user && str_ends_with($user->email, '@lecturers.ed')) {
    return redirect()->route('lecturer.dashboard');
}

// 2. If it's a student, send them to the student route
if ($user && str_ends_with($user->email, '@students.ed')) {
    return redirect()->route('student.dashboard');
}
    
    // 3. If it's a random email, log them out and block them with an error
    auth()->logout();
    return redirect()->route('login')->withErrors([
        'email' => 'Access denied. You must register using an authorized institution email address.'
    ]); 
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Isolated Role-Based Dashboards
Route::middleware(['auth'])->group(function () {

    // Student Dashboard
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');

    // Lecturer Dashboard
    Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');

    Route::get('/admin/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard');

    // Profile management paths
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';