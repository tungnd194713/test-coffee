<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function login($request);

    public function register($request);

    public function deleteHistory($request);

    // public function sendResetMail($request);
}
