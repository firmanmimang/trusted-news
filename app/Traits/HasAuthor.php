<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    public function author():User
    {
        return $this->authorRelation;
    }

    public function authoredBy(User $user)
    {
        $this->authorRelation()->associate($user);
        $this->unsetRelation('authorRelation');
    }

    public function authorRelation():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isAuthoredBy(User $user):bool
    {
        return $this->author()->matches($user);
    }
}
