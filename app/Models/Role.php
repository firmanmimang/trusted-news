<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use HasFactory;

    // Make const for guard_name
    const GUARD_CMS = 'cms';

    const SUPER_ADMIN = 'super admin';
}
