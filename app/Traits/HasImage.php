<?php

namespace App\Traits;

use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function replaceImage($file)
    {
        if($this->image) $this->deleteImage();
        return Storage::url($file->storeAs('user-images', md5($file->getClientOriginalName()).'.'.$file->extension()));
    }

    public function replaceImageNews($file)
    {
        if($this->image) $this->deleteImage();
        return Storage::url($file->storeAs('news-images', md5($file->getClientOriginalName()).'.'.$file->extension()));
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
            ImageHelper::DEFAULT_USER_IMAGE;
    }
}
