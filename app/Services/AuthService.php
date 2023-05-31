<?php

namespace App\Services;

use App\Mail\ResetPassword;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthService implements AuthServiceInterface
{
    public function login($request) {
        if (!Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => User::ROLE_USER], $request->remember)) {
            throw new \Exception("Invalid credentials");
        }
        $request->session()->regenerate();
    }

    public function register($request) {
        $user = User::create([
            'name' => $request?->name,
            'age' => $request?->age,
            'email' => $request?->email,
            'password' => Hash::make($request?->email),
            'role' => User::ROLE_USER,
        ]);
        if ($user) {
            if (Auth::guard('user')->login($user)) {
                $request->session()->regenerate();
            }
        }
    }

    public function logout($request) {
        Auth::guard('user')->logout();
        $request->session()->invalidate();
    }

    public function sendResetMail($request) {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        if (!User::where('email', $request->email)->first()) {
            return back()->withErrors([
                'email' => 'This email has not been registered',
            ]);
        }
        $token = Str::random(32);
        PasswordResets::insert(['email' => $request->email, 'token' => $token]);

        $resetURL = URL::temporarySignedRoute('show.reset.password', now()->addDays(7), ['token' => $token]);
        Mail::to($request->email)->send(new ResetPassword($resetURL));
    }
}
