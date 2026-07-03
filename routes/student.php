<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\QuizController as StudentQuizController;

Route::prefix('student')->middleware(['auth', 'verified'])->group(function() {
    
    Route::prefix('quizzes')->group(function() {
        
        // List available quizzes
        Route::get('/', [StudentQuizController::class, 'index'])
            ->name('student.quizzes.index');
        
        // Start a quiz
        Route::get('{id}/start', [StudentQuizController::class, 'start'])
            ->name('student.quizzes.start');
        
        // Save answer (AJAX)
        Route::post('attempt/{attemptId}/save-answer', [StudentQuizController::class, 'saveAnswer'])
            ->name('student.quizzes.save-answer');
        
        // Submit quiz manually
        Route::post('attempt/{attemptId}/submit', [StudentQuizController::class, 'submitQuiz'])
            ->name('student.quizzes.submit');
        
        // View results
        Route::get('attempt/{attemptId}/results', [StudentQuizController::class, 'results'])
            ->name('student.quizzes.results');
    });
}); 
