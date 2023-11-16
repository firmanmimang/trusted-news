<?php

namespace App\Models;

use App\Traits\HasImage;
use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Tonysm\RichTextLaravel\Casts\AsRichTextContent;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class News extends Model
{
    use HasFactory, ModelHelpers, HasImage;
    use HasRichText;

    const TABLE = 'news';

    protected $table = self::TABLE;

    // protected $fillable = ['title', 'excerpt', 'body'];
    protected $guarded = ['id'];

    protected $with = ['category', 'author'];

    protected $casts = [
        'publish_status' => 'boolean',
        'comment_status' => 'boolean',
        'is_highlight' => 'boolean',
        'is_crawl' => 'boolean',
        'published_at' => 'datetime',
        'body' => AsRichTextContent::class,
    ];

    protected $richTextFields = [
        'body',
    ];

    /**
     * filter searching, category, author purposes on post index
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where(function($query) use ($search) {
                 $query->where('title', 'like', '%' . $search . '%')
                            //  ->orWhere('body', 'like', '%' . $search . '%')
                            ;
            });
        });
 
        $query->when($filters['category'] ?? false, function($query, $category){
            return $query->whereHas('category', function($query) use ($category){
                $query->where('slug', $category);
            });
        });

        $query->when($filters['author'] ?? false, fn($query, $author) =>
            $query->whereHas('author', fn($query) =>
                $query->where('username', $author)
            )
        );

        $query->when($filters['q'] ?? false, function($query, $search) {
            return $query->where(function($query) use ($search) {
                 $query->where('title', 'like', '%' . $search . '%')
                            //  ->orWhere('body', 'like', '%' . $search . '%')
                            ;
            });
        });

        $query->when($filters['s'] ?? false, function($query, $source) {
            return $query->where(function($query) use ($source) {
                 $query->where('source_crawl', $source)
                            ;
            });
        });
    }

    public function scopeCrawlStatus($query, bool $status)
    {
        return $query->where('is_crawl', $status);
    }

    public static function generateExcerpt(string $string, $limit = 200):string
    {
        return trim(Str::limit(preg_replace("/(&nbsp;|&amp;|amp;lt;i|amp;gt;|amp;lt;\/i|amp;|ampamp;|#039;|&lt;i&gt;|&lt;\/i&gt;|&lt;|&gt;|\'|&quot;|lt;i|gt;|lt;\/i|lt;I|\\|\/|\s{2,}|:|lt;)/", ' ', strip_tags($string))        , $limit));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    //ambil slug scr default
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
