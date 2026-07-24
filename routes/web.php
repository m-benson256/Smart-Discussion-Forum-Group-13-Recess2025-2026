<?php

use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AdministratorController;
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
use App\Http\Controllers\UserInterestsController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ParticipationController;

// 1. Welcome Page
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // 1.1 Onboarding Page
    Route::view('/onboarding', 'onboarding')->name('onboarding');

    Route::view('/pending-approval', 'auth.pending-approval')->name('pending-approval');

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
    Route::post('/attempts/{attempt}/answer', [QuizAttemptController::class, 'saveAnswer']);

    Route::get('/student/performance-stats', [QuizAttemptController::class, 'performanceStats']);
   Route::get('/student/active-quiz', [QuizAttemptController::class, 'activeQuiz']);
    
   Route::get('/groups/{group}/requests', [GroupController::class, 'pendingRequests']);
Route::post('/group-requests/{groupJoinRequest}/approve', [GroupController::class, 'approveRequest']);
Route::post('/group-requests/{groupJoinRequest}/reject', [GroupController::class, 'rejectRequest']);


});

// 2. Main Auth Traffic Controller (Handles redirecting /dashboard based on email domain)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // 0. If it's an admin, send them to the admin route
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // 1. If it's a lecturer, check verification status before granting access
    if ($user && str_ends_with($user->email, '@lecturers.ed')) {
        if ($user->verification_status === 'pending') {
            return redirect()->route('pending-approval');
        }

        if ($user->verification_status === 'rejected') {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your lecturer account was not approved. Please contact the administrator.',
            ]);
        }

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
Route::post('/logout', function (Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');
    

// 3. Isolated Role-Based Dashboards
Route::middleware(['auth'])->group(function () {

    // Student Dashboard
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/dashboard', [StudentController::class, 'index'])
    ->middleware(['auth', 'blacklisted'])
    ->name('student.dashboard');

    // Lecturer Dashboard
    Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');

    Route::get('/admin/dashboard', [AdministratorController::class, 'index'])->name('admin.dashboard');
    Route::patch('/administrator/users/{user}/verify', [AdministratorController::class, 'verifyLecturer'])->name('admin.users.verify');
    Route::patch('/administrator/users/{user}/reject', [AdministratorController::class, 'rejectLecturer'])->name('admin.users.reject');
    Route::post('/admin/warnings', [AdministratorController::class, 'storeWarning'])->name('admin.warnings.store');
    Route::post('/admin/groups/{id}/toggle-status', [AdministratorController::class, 'toggleGroupStatus'])->name('admin.groups.toggle-status');
    Route::post('/admin/users/{id}/block', [AdministratorController::class, 'blockUser'])->name('admin.users.block');
    Route::post('/admin/users/{id}/unblock', [AdministratorController::class, 'unblockUser'])->name('admin.users.unblock');

    // Profile management paths
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');

    Route::get('/topics', [TopicController::class, 'index']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::get('/topics/{topic}', [TopicController::class, 'show']);
    Route::put('/topics/{topic}', [TopicController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);
   
    Route::get('/topics/{topic}/export-pdf', [MessageController::class, 'exportPdf'])->name('topics.export-pdf');

    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/lecturer/reports', [QuizAttemptController::class, 'report']);
     Route::get('/lecturer/search', [SearchController::class, 'search']);
     Route::get('/student/search', [SearchController::class, 'studentSearch']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::post('/groups/{group}/join', [GroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave']);
        
    Route::get('/my-pending-requests', [GroupController::class, 'myPendingRequests']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::post('/groups/{group}/join', [GroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave']);
    Route::post('/groups/{group}/request-join', [GroupController::class, 'requestToJoin']);
     
Route::get('/topics/{topic}/messages', [MessageController::class, 'index']);
Route::post('/topics/{topic}/messages', [MessageController::class, 'store']);
Route::post('/messages/{message}/flag', [MessageController::class, 'toggleFlag']);
Route::post('/messages/{message}/react', [MessageController::class, 'toggleReaction']);
Route::get('/announcements', [AnnouncementsController::class, 'index']);


Route::get('/user-interests', [UserInterestsController::class, 'index']);

Route::post('/topics/{topic}/view', [TopicController::class, 'recordView']);

Route::get('/recommended-topics', [RecommendationController::class, 'index']);


 Route::get('/internal/interaction-data', [RecommendationController::class, 'interactionData']);

 Route::post('/messages/{message}/like', [MessageController::class, 'toggleLike']);
 
 Route::get('/topics/{topic}/preview', [TopicController::class, 'publicPreview'])->name('topics.preview');

Route::get('/lecturer/participation/criteria', [ParticipationController::class, 'getCriteria']);
Route::post('/lecturer/participation/criteria', [ParticipationController::class, 'saveCriteria']);
Route::get('/lecturer/participation/scores', [ParticipationController::class, 'scores']);
 });


 




require __DIR__.'/auth.php';
