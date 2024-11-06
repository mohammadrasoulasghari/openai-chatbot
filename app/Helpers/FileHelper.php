<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function storeTemporaryFile($file, $filename = 'temp_audio.mp4')
    {
        return $file->storeAs('audio', $filename, 'public');
    }

    public static function deleteTemporaryFile($filePath): void
    {
        Storage::disk('public')->delete($filePath);
    }
}
