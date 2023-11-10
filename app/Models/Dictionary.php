<?php

namespace App\Models;

use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory, ModelHelpers;

    const TABLE = 'dictionaries';

    protected $table = self::TABLE;

    protected $fillable = [
        'word', 'slug', 'category',
    ];
}
