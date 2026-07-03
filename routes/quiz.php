 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Quiz\AutoSubmitController;

// Auto-submit expired quizzes (Cron job)
Route::get('auto-submit-quizzes', [AutoSubmitController::class, 'autoSubmitExpiredQuizzes'])
    ->name('auto-submit.quizzes');

// Test auto-submit (for testing)
Route::get('test-auto-submit', [AutoSubmitController::class, 'autoSubmitExpiredQuizzes'])
    ->name('test.auto-submit');
