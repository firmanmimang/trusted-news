<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    public function before(User $user): bool|null
    {
        if ($user->hasRole('super admin')) return true;
        
        return null;
    }

    public function create(?User $user): bool
    {
        return $user?->hasPermissionTo('comment')??false;
    }

    public function update(User $user, Comment $comment): bool
    {
        if(!$user->hasPermissionTo('comment')) return false;
        
        return $comment->isAuthoredBy($user);
    }

    public function delete(User $user, Comment $comment): bool
    {
        if(!$user->hasPermissionTo('comment')) return false;
        return $comment->isAuthoredBy($user);
    }
}
