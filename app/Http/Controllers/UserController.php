<?php

namespace App\Http\Controllers;

use App\Services\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {
        
    }

    public function detail(Request $request) {
        return response()->json(['data' => $request->user(), 'status' => 200]);
    }

    public function updateProfile(Request $request) {
        return response()->json($this->userService->updateProfile($request));
    }
}
