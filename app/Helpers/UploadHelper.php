<?php

namespace App\Helpers;

class UploadHelper
{
    /**
     * @param object|null $file
     * @param string      $path
     * @param string|null $oldFile
     * @return string|null
     */
    public static function handleUpload($file, $path = 'uploads/', $oldFile = null)
    {
        if (!$file) return $oldFile;

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        if ($oldFile && file_exists(public_path($oldFile))) {
            unlink(public_path($oldFile));
        }

        $fileName = uniqid() . time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path($path), $fileName);

        return $path . $fileName;
    }
}
