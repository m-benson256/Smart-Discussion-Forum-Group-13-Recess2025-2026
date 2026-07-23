
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\User_interests;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\QuizAttemptController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| 1. Public API Routes (No Token Needed)
|--------------------------------------------------------------------------
*/

// Desktop Login (Uses Breeze validation + issues token)
Route::post('/desktop/login', function (LoginRequest $request) {
    $request->authenticate();
    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('javafx-desktop-token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'token' => $token,
        'user' => [
            'name' => $user->name,
             'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);
});

// Desktop Registration (Matches your register.blade.php custom fields)
Route::post('/desktop/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@students.ed') && !str_ends_with($value, '@lecturers.ed')) {
                    $fail('You must register using an authorized institution email address.');
                }
            },
        ],
        'password' => 'required|string|min:8',

        'academic_category' => [
            Rule::requiredIf(fn () => str_ends_with($request->email, '@students.ed')),
            'nullable',
            'string',
        ],

        // degree_program belongs to lecturers
        'degree_program' => [
            Rule::requiredIf(fn () => str_ends_with($request->email, '@lecturers.ed')),
            'nullable',
            'string',
        ],

        'desk_contact_number' => [
            Rule::requiredIf(fn () => str_ends_with($request->email, '@lecturers.ed')),
            'nullable',
            'string',
        ],
    ]);

    $role = str_ends_with($request->email, '@lecturers.ed') ? 'lecturer' : 'student';

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $role,
        'status' => 'active',
        'category' => $request->academic_category,
        'DegreeType' => $request->degree_program,
        'contact' => $request->desk_contact_number,
    ]);

    $token = $user->createToken('javafx-desktop-token')->plainTextToken;

    return response()->json([
    'status' => 'success',
    'token' => $token,
    'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $role,
    ],
    'message' => 'Account registered successfully!'
], 201);
});


Route::middleware(['auth:sanctum', 'admin'])->prefix('desktop/admin')->group(function () {
    // e.g. Route::get('/dashboard', [AdminController::class, 'dashboard']);
    // e.g. Route::get('/reports', [AdminController::class, 'reports']);
});

/*
|--------------------------------------------------------------------------
| 2. Protected API Routes (JavaFX Must Provide the Sanctum Bearer Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Get the current logged-in user profile info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Onboarding Endpoint (Saves interests from your custom array pattern)
    Route::post('/desktop/onboarding', function (Request $request) {
        $interestLabels = [
            'ml' => 'Machine Learning', 'web' => 'Web Development', 'db' => 'Database Systems',
            'mobile' => 'Mobile Engineering', 'security' => 'Cybersecurity', 'design' => 'UI/UX Design',
            'cloud' => 'Cloud Computing', 'data' => 'Data Science', 'ai' => 'Artificial Intelligence',
        ];

        $selectedInterestIds = collect($request->input('interests', []))
            ->map(fn (string $code) => $interestLabels[$code] ?? null)
            ->filter()
            ->map(function (string $name) {
                return User_interests::firstOrCreate(['InterestName' => $name])->InterestID;
            })
            ->values()
            ->all();

        $user = $request->user();
        $user->interests()->sync($selectedInterestIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Interests updated successfully.',
            'next_destination' => 'login'
        ]);
    });

    // Topic / Discussion Board APIs
    Route::get('/desktop/topics', [TopicController::class, 'index']); // JavaFX can download the topic list
    Route::post('/desktop/topics', [TopicController::class, 'store']); // Create topic from Java
    Route::get('/desktop/topics/{topic}/messages', [MessageController::class, 'index']); // Pull messages
    Route::post('/desktop/topics/{topic}/messages', [MessageController::class, 'store']); // Post message

    // Groups
    Route::get('/desktop/groups', [GroupController::class, 'index']);
    Route::post('/desktop/groups/{group}/join', [GroupController::class, 'join']);
    Route::get('/desktop/groups/{group}', [GroupController::class, 'show']);
    Route::post('/desktop/groups/{group}/leave', [GroupController::class, 'leave']);

    Route::post('/desktop/groups', [GroupController::class, 'store']);
    Route::get('/desktop/groups/{group}/requests', [GroupController::class, 'pendingRequests']);
    Route::post('/desktop/group-requests/{groupJoinRequest}/approve', [GroupController::class, 'approveRequest']);
    Route::post('/desktop/group-requests/{groupJoinRequest}/reject', [GroupController::class, 'rejectRequest']);
    Route::get('/desktop/my-pending-requests', [GroupController::class, 'myPendingRequests']);

    Route::get('/desktop/topics', [TopicController::class, 'index']);
    Route::post('/desktop/topics', [TopicController::class, 'store']);

    // Quizzes (Student Side)
    Route::get('/desktop/student/quizzes', [QuizAttemptController::class, 'index']);
    Route::post('/desktop/quizzes/{quiz}/start', [QuizAttemptController::class, 'start']);

    Route::post('/desktop/messages/{message}/like', [MessageController::class, 'toggleLike']);
Route::post('/desktop/messages/{message}/react', [MessageController::class, 'toggleReaction']);
Route::post('/desktop/messages/{message}/flag', [MessageController::class, 'toggleFlag']);

Route::get('/desktop/recommended-topics', [RecommendationController::class, 'index']);

<<<<<<< HEAD
Route::post('/desktop/attempts/{attempt}/answer', [QuizAttemptController::class, 'saveAnswer']);
Route::post('/desktop/attempts/{attempt}/submit', [QuizAttemptController::class, 'submit']);

=======
// Quizzes (Lecturer Side)
Route::get('/desktop/lecturer/dashboard-stats', [LecturerController::class, 'dashboardStats']);
Route::get('/desktop/lecturer/quizzes', [QuizController::class, 'index']);
Route::post('/desktop/quizzes', [QuizController::class, 'store']);
Route::get('/desktop/quizzes/{quiz}', [QuizController::class, 'show']);
Route::put('/desktop/quizzes/{quiz}', [QuizController::class, 'update']);
Route::post('/desktop/quizzes/{quiz}/publish', [QuizController::class, 'publish']);

Route::post('/desktop/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store']);
Route::put('/desktop/questions/{question}', [QuizQuestionController::class, 'update']);
Route::delete('/desktop/questions/{question}', [QuizQuestionController::class, 'destroy']);
Route::get('/desktop/lecturer/reports', [QuizAttemptController::class, 'report']);

Route::get('/desktop/categories', [CategoryController::class, 'index']);

//Announcements
Route::get('/desktop/announcements', [AnnouncementsController::class, 'index']);
Route::post('/desktop/quizzes/{quiz}/announce', [AnnouncementsController::class, 'store']);

//Participation
Route::get('/desktop/lecturer/participation/criteria', [ParticipationController::class, 'getCriteria']);
Route::post('/desktop/lecturer/participation/criteria', [ParticipationController::class, 'saveCriteria']);
Route::get('/desktop/lecturer/participation/scores', [ParticipationController::class, 'scores']);
Route::get('/desktop/lecturer/search', [SearchController::class,'search']);
>>>>>>> main

Route::get('/desktop/student/performance-stats', [QuizAttemptController::class, 'performanceStats']);
Route::get('/desktop/student/active-quiz', [QuizAttemptController::class, 'activeQuiz']);
});