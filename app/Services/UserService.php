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
                'avatar' => '',
                'address' => 'string',
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'phone_number' => 'string',
                'gender' => 'numeric',
                'birthday' => 'string',
            ]);
        } catch (\Exception $e) {
            return ['data' => $e->getMessage(), 'status' => 402];
        }

        if (isset($validatedData['email'])) {
            $usedUser = User::where('email', $validatedData['email'])->first();
            if ($usedUser?->id !== $user->id) {
                return ['data' => 'Email used', 'status' => 401];
            }
        }


        // Update user profile
        $user->update($validatedData);
        // Handle avatar update if provided
        if ($request->has('avatar')) {
            $avatar = $request->avatar; //your base64 encoded data
            $avatarPath = S3Helper::uploadToS3($avatar, 'user_avatars');
            $oldAvatar = $user->avatar;
            $user->avatar = $avatarPath;
            $user->save();
            S3Helper::deleteFromS3($oldAvatar, 'user_avatars');
        }

        return ['message' => 'Profile updated successfully', 'status' => 200];
    }
}
