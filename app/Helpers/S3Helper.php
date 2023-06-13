<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Helper
{
    public static function uploadToS3($file, $position)
    {
        $avatarName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $filePath = $position . '/' . $avatarName;

        // Upload the file to AWS S3
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        return Storage::disk('s3')->url($filePath);
    }
}