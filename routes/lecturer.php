<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;

Route::prefix('lecturer')->middleware(['auth', 'verified'])->group(function() {
    
    // Quiz CRUD
    Route::resource('quizzes', LecturerQuizController::class);
    
    // Add question to quiz
    Route::post('quizzes/{id}/add-question', [LecturerQuizController::class, 'addQuestion'])
        ->name('lecturer.quizzes.add-question');
    
    // Delete question from quiz
    Route::delete('quizzes/{quizId}/questions/{questionId}', [LecturerQuizController::class, 'deleteQuestion'])
        ->name('lecturer.quizzes.delete-question');
    
    // View quiz results
    Route::get('quizzes/{id}/results', [LecturerQuizController::class, 'results'])
        ->name('lecturer.quizzes.results');
}); 
