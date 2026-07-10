<?php

use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\SearchController;
use App\Models\Quiz;
use App\Models\User_interests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Welcome Page
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // 1.1 Onboarding Page
    Route::view('/onboarding', 'onboarding')->name('onboarding');

    Route::post('/onboarding', function (Request $request) {
        $allowedInterests = ['ml', 'web', 'db', 'mobile', 'security', 'design', 'cloud', 'data', 'ai'];
        $interestLabels = [
            'ml' => 'Machine Learning',
            'web' => 'Web Development',
            'db' => 'Database Systems',
            'mobile' => 'Mobile Engineering',
            'security' => 'Cybersecurity',
            'design' => 'UI/UX Design',
            'cloud' => 'Cloud Computing',
            'data' => 'Data Science',
            'ai' => 'Artificial Intelligence',
        ];

        $request->validate([
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'in:'.implode(',', $allowedInterests)],
        ]);

        $selectedInterestIds = collect($request->input('interests', []))
            ->map(fn (string $interestCode) => $interestLabels[$interestCode] ?? null)
            ->filter()
            ->map(function (string $interestName) {
                return User_interests::firstOrCreate(['InterestName' => $interestName])->InterestID;
            })
            ->values()
            ->all();

        $user = $request->user();
        $user->interests()->sync($selectedInterestIds);

        if ($user && str_ends_with($user->email, '@lecturers.ed')) {
            return redirect()->route('lecturer.dashboard');
        }

        if ($user && str_ends_with($user->email, '@students.ed')) {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('dashboard');
    })->name('onboarding.complete');

    Route::get('/student/quizzes/{quiz}/attempt', function (Quiz $quiz) {
        return view('student.quizzes.attempt', ['quizId' => $quiz->id]);
    })->name('quiz.attempt');

    Route::get('/lecturer/quizzes', [QuizController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
    Route::post('/quizzes/{quiz}/publish', [QuizController::class, 'publish']);
    Route::post('/quizzes/{quiz}/announce', [AnnouncementsController::class, 'store']);

    Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store']);
    Route::put('/questions/{question}', [QuizQuestionController::class, 'update']);
    Route::delete('/questions/{question}', [QuizQuestionController::class, 'destroy']);

    Route::get('/lecturer/quizzes/create', function () {
    return view('lecturer.quizzes.create', [
        'categories' => \App\Models\Category::orderBy('CategoryName')->get(),
    ]);
     })->name('quiz.create');
    Route::get('/lecturer/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quiz.edit');

    Route::get('/student/quizzes', [QuizAttemptController::class, 'index'])->name('student.quizzes');
    Route::get('/student/dashboard/quizzes', function () {
        return redirect()->route('student.quizzes');
    });
    Route::post('/quizzes/{quiz}/start', [QuizAttemptController::class, 'start']);
    Route::post('/attempts/{attempt}/submit', [QuizAttemptController::class, 'submit']);
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
        'email' => 'Access denied. You must register using an authorized institution email address.',
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

    Route::get('/topics', [TopicController::class, 'index']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::get('/topics/{topic}', [TopicController::class, 'show']);
    Route::put('/topics/{topic}', [TopicController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);

    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/lecturer/reports', [QuizAttemptController::class, 'report']);
     Route::get('/lecturer/search', [SearchController::class, 'search']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::post('/groups/{group}/join', [GroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave']);

    Route::get('/topics/{topic}/messages', [MessageController::class, 'index']);
    Route::post('/topics/{topic}/messages', [MessageController::class, 'store']);
    Route::post('/messages/{message}/flag', [MessageController::class, 'toggleFlag']);

});

require __DIR__.'/auth.php';
