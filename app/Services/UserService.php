<?php

namespace App\Services;

use App\Helpers\S3Helper;
use App\Mail\ResetPassword;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    public function updateProfile($request) {
        $user = $request->user();
        try {
            $validatedData = $request->validate([
                'name' => 'string',
                'email' => 'email',
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'address' => 'string',
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'phone_number' => 'string',
                'gender' => 'numeric',
                'birthday' => 'date',
            ]);
        } catch (\Exception $e) {
            return ['data' => $e, 'status' => 402];
        }
        
        $usedUser = User::where('email', $validatedData['email'])->first();
        if ($usedUser?->id == $user->id) {
            return ['data' => 'Email used', 'status' => 401];
        }

        // Update user profile
        $user->update($validatedData);

        // Handle avatar update if provided
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = S3Helper::uploadToS3($avatar, 'user_avatars');
            $user->avatar = $avatarPath;
            $user->save();
        }

        return ['message' => 'Profile updated successfully', 'status' => 200];
    }
}