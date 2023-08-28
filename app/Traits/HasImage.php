<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasImage
{
    public function replaceImage($file)
    {
        if($this->image) $this->deleteImage();
        return Storage::url($file->storeAs('user-images', md5($file->getClientOriginalName()).'.'.$file->extension()));
    }

    public function deleteImage()
    {
        if($this->image)Storage::delete(str_replace('/storage', '', $this->image));
    }

    /**
     * Get avatar image url
     * @return string
     */
    public function getImageImageAttribute(): string
    {
        return $this->image ?
            $this->image :
            '/assets/default/user-default.png';
    }
}
