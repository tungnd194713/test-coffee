<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Helper
{
    public static function uploadToS3($file, $position)
    {
        $replace = substr($file, 0, strpos($file, ',')+1);
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $avatar = str_replace($replace, '', $file);
        $avatar = str_replace(' ', '+', $avatar);
        $avatarName = Str::random(10).'.'.$extension;
        $filePath = $position . '/' . $avatarName;
        // Upload the file to AWS S3
        Storage::disk('s3')->put($filePath, base64_decode($avatar));

        return Storage::disk('s3')->url($filePath);
    }
}
