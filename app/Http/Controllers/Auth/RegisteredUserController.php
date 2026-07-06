<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request, CreateNewUser $creator): RedirectResponse
    {
        $request->validate([
            'terms' => ['required', 'accepted'],
        ]);

        $email = strtolower($request->string('email')->toString());

        if (! str_ends_with($email, '@students.ed') && ! str_ends_with($email, '@lecturers.ed')) {
            return back()->withErrors([
                'email' => 'Registration is restricted. You must use an official @students.ed or @lecturers.ed account.',
            ])->withInput();
        }

        $user = $creator->create($request->all());

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('onboarding');
    }
}
