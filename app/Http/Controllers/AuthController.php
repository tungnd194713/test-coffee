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

    public function login(LoginRequest $request) {
        $data = $this->authService->login($request);

        return response()->json($data, 201);
    }

    public function register(RegisterRequest $request) {
        $data = $this->authService->register($request);

        return response()->json($data, 201);
    }
}
