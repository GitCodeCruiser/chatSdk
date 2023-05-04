<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class UploadFileService
{
    public function compressFile($image, $path, $imageName)
    {
        $image_resize = Image::make(public_path($path) . '/' . $imageName);
        $image_resize->fit(250);
        $image_resize->save(public_path($path) . '/' . 'compress/' . $imageName);

        return $imageName;
    }

    public function uploadFile($image, $path, $imagePrefix = null)
    {
        $imageName = $imagePrefix . '-' . uniqid() . '-' . $image->getClientOriginalName();
        $image->move(public_path($path), $imageName);

        return $imageName;
    }

    public function deleteFile($file, $path)
    {
        if (file_exists(public_path() . '/' . $path . '/' . $file)) {
            @unlink(public_path() . '/' . $path . '/' . $file);
            return true;
        } else {
            return false;
        }
    }
}
