<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VectorizeNews extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'vectorize_news';

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }
}
