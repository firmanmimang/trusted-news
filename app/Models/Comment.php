<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, ModelHelpers, HasAuthor;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function news():BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
