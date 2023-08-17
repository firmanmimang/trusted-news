<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    const GOOGLE = 'google';
    const GITHUB = 'github';

    protected $fillable = [
        'user_id',
        'provider_type',
        'provider_id',
        'detail',
    ];

    protected $casts = [
        'detail' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
