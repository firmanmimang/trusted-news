<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelHelpers
{
    public function matches(self $model): bool
    {
        return $this->id === $model->id;
    }

    public function uniqueSlug(string $name): string
    {
        return $this->where('slug', Str::slug($name))->exists() ? Str::slug($name).Str::random('2') : Str::slug($name);
    }

    public function uniqueUsername(string $name): string
    {
        return $this->where('username', Str::slug($name))->exists() ? Str::slug($name).Str::random('2') : Str::slug($name);
    }
}
