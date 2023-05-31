<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Models\PasswordResets;
use App\Models\User;
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function __construct(protected AuthServiceInterface $authService)
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        try {
            $this->authService->login($request);
            return redirect(route('home'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(RegisterRequest $request) {
        $this->authService->register($request);
        return redirect(route('home'));
    }

    public function logout(Request $request) {
        $this->authService->logout($request);
        return redirect(route('show.login'));
    }

    public function showForgotForm() {
        return view('auth.passwords.email');
    }

    public function sendResetMail(ResetPasswordRequest $request) {
        $this->authService->sendResetMail($request);
        return back()->with('status', 'Reset password url sent, check your mail!');
    }

    public function showResetForm() {
        return view('auth.passwords.reset');
    }
}
