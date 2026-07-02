<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

   
   
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'], 
        ]);

        // 2. THE CHOSEN CONSTRAINT: Block invalid domains before saving to database
    $email = $request->email;
    if (!str_ends_with($email, '@students.ed') && !str_ends_with($email, '@lecturers.ed')) {
        return back()->withErrors([
            'email' => 'Registration is restricted. You must use an official @students.ed or @lecturers.ed account.'
        ])->withInput(); // Keeps their name filled in so they don't lose progress
    }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Dynamic redirection right after account registration
    if (str_ends_with($user->email, '@lecturers.ed')) {
        return redirect(route('lecturer.dashboard'));
    }

    if (str_ends_with($user->email, '@students.ed')) {
        return redirect(route('student.dashboard'));
    }

   

        return redirect(route('dashboard', absolute: false));
    }
}
