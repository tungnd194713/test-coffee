<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function login($request);

    public function register($request);

    public function logout($request);

    public function sendResetMail($request);
}
