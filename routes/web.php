<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;

use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Quiz\AutoSubmitController;

// 1. Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Main Auth Traffic Controller (Handles redirecting /dashboard based on email domain)
Route::get('/dashboard', function () {
   $user = auth()->user();
    
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

    // Profile management paths
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ============================================
// QUIZ MODULE ROUTES (Added by [Your Name])
// ============================================

// Lecturer Quiz Routes
Route::prefix('lecturer')->middleware(['auth', 'verified'])->group(function() {
    
    Route::resource('quizzes', LecturerQuizController::class);
    
    Route::post('quizzes/{id}/add-question', [LecturerQuizController::class, 'addQuestion'])
        ->name('lecturer.quizzes.add-question');
    
    Route::delete('quizzes/{quizId}/questions/{questionId}', [LecturerQuizController::class, 'deleteQuestion'])
        ->name('lecturer.quizzes.delete-question');
    
    Route::get('quizzes/{id}/results', [LecturerQuizController::class, 'results'])
        ->name('lecturer.quizzes.results');
});

// Student Quiz Routes
Route::prefix('student')->middleware(['auth', 'verified'])->group(function() {
    
    Route::prefix('quizzes')->group(function() {
        
        Route::get('/', [StudentQuizController::class, 'index'])
            ->name('student.quizzes.index');
        
        Route::get('{id}/start', [StudentQuizController::class, 'start'])
            ->name('student.quizzes.start');
        
        Route::post('attempt/{attemptId}/save-answer', [StudentQuizController::class, 'saveAnswer'])
            ->name('student.quizzes.save-answer');
        
        Route::post('attempt/{attemptId}/submit', [StudentQuizController::class, 'submitQuiz'])
            ->name('student.quizzes.submit');
        
        Route::get('attempt/{attemptId}/results', [StudentQuizController::class, 'results'])
            ->name('student.quizzes.results');
    });
});

// Auto-Submit Routes
Route::get('auto-submit-quizzes', [AutoSubmitController::class, 'autoSubmitExpiredQuizzes'])
    ->name('auto-submit.quizzes');


require __DIR__.'/lecturer.php';
require __DIR__.'/student.php';
require __DIR__.'/quiz.php';