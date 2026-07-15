
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
            'email' => $user->email,
            'role' => str_ends_with($user->email, '@lecturers.ed') ? 'lecturer' : 'student'
        ]
    ]);
});

// Desktop Registration (Matches your register.blade.php custom fields)
Route::post('/desktop/register', function (Request $request) {
    // Validate matching the fields you have in your blade view
   $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8',
    
    // Correctly require academic_category if email ends with @students.ed
    'academic_category' => [
        Rule::requiredIf(fn () => str_ends_with($request->email, '@students.ed')),
        'nullable',
        'string',
    ],
    
    // Correctly require degree_program if email ends with @students.ed
    'degree_program' => [
        Rule::requiredIf(fn () => str_ends_with($request->email, '@lecturers.ed')),
        'nullable',
        'string',
    ], 
       
    
    // Correctly require desk_contact_number if email ends with @lecturers.ed
    'desk_contact_number' => [
        Rule::requiredIf(fn () => str_ends_with($request->email, '@lecturers.ed')),
        'nullable',
        'string',
    ],
]);

    // Create the core user account
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        // If your database profile tables have specific columns for categories/degrees, save them here
    ]);

    $token = $user->createToken('javafx-desktop-token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'token' => $token,
        'message' => 'Account registered successfully!'
    ], 201);
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
            'next_destination' => str_ends_with($user->email, '@lecturers.ed') ? 'lecturer_dashboard' : 'student_dashboard'
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

    // Quizzes (Student Side)
    Route::get('/desktop/student/quizzes', [QuizAttemptController::class, 'index']);
    Route::post('/desktop/quizzes/{quiz}/start', [QuizAttemptController::class, 'start']);
});