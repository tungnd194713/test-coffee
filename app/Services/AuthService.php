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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AuthService implements AuthServiceInterface
{
    public function login($request) {
        if (!Auth::guard('user')->attempt(['username' => $request->username, 'password' => $request->password, 'role' => $request->role], $request->remember)) {
            abort(401, 'Invalid credentials');
        }

        $user = User::where('username', $request->username)->with('restaurants')->first();
        $token = $user->createToken('authToken')->plainTextToken;
        return ['user' => $user, 'access_token' => $token, 'status' => 200];
    }

    public function register($request) {
        $user = User::create([
            'username' => $request?->username,
            'password' => Hash::make($request?->password),
            'role' => $request->role,
            'name' => '',
            'address' => '',
            'latitude' => 0,
            'longitude' => 0,
        ]);
        if ($user) {
            if (Auth::guard('user')->attempt(['username' => $request->username, 'password' => $request->password, 'role' => $request->role])) {
                $token = $user->createToken('authToken')->plainTextToken;
                return ['user' => $user, 'access_token' => $token, 'status' => 200];
            }
        }
    }

    public function deleteHistory($request) {
        $user = auth('sanctum')->user();
        $history = json_decode($user->search_history);
        if (!$history || count($history) == 0) {
            $history = [];
        }
        $key = array_search($request->search, $history);
        if ($key !== false) {
            unset($history[$key]);
        }
        $user->search_history = json_encode($history);
        $user->save();
    }

    // public function sendResetMail($request) {
    //     $request->validate([
    //         'username' => ['required', 'username', 'max:255'],
    //     ]);

    //     if (!User::where('username', $request->username)->first()) {
    //         return back()->withErrors([
    //             'username' => 'This username has not been registered',
    //         ]);
    //     }
    //     $token = Str::random(32);
    //     PasswordResets::insert(['username' => $request->username, 'token' => $token]);

    //     $resetURL = URL::temporarySignedRoute('show.reset.password', now()->addDays(7), ['token' => $token]);
    //     Mail::to($request->username)->send(new ResetPassword($resetURL));
    // }
}
