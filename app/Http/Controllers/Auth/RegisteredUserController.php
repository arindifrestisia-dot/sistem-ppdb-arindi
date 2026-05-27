<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PpdbNotificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly PpdbNotificationService $notifications,
    ) {
    }

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => Str::lower((string) $request->input('username')),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash:ascii', 'min:4', 'max:50', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => User::ROLE_PARENT,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $this->notifications->send('account_registered', $user);

        Auth::login($user);

        return redirect()
            ->route('dashboard');
    }
}
