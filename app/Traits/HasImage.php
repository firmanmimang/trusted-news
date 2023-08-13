<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasImage
{
    /**
     * Upload image for one to one relationship on polymorph relation
     * @param File input and name
     * @return void
     */
    // public function replaceImage($file, string $name)
    // {
    //     if ($this->getFirstMedia($name)) $this->deleteMedia($this->getFirstMedia($name)->id);
    //     return $this->addMedia($file)->usingName(Str::random(20))->usingFileName(md5($file->getClientOriginalName()).'.'.$file->extension())->toMediaCollection($name);
    // }

    // public function replaceImageArray($file, string $name, $index)
    // {
    //     if (isset($this->getMedia($name)[$index])&&$this->getMedia($name)[$index]) $this->deleteMedia($this->getMedia($name)[0]->id);
    //     return $this->addMedia($file)->usingName(Str::random(20))->usingFileName(md5($file->getClientOriginalName()).'.'.$file->extension())->toMediaCollection($name);
    // }

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
