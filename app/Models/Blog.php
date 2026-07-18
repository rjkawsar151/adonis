<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'id', 'slug', 'title', 'excerpt', 'coverImage', 'contentHtml',
        'seoTitle', 'seoDescription', 'status', 'createdAt', 'updatedAt',
        'author_id', 'category_id', 'is_featured', 'is_pinned', 'reading_time', 'published_at',
        'focus_keyword', 'secondary_keywords', 'canonical_url', 'robots_index', 'robots_follow',
        'og_title', 'og_description', 'og_image', 'twitter_title', 'twitter_description',
        'twitter_image', 'schema_type', 'breadcrumb_title'
    ];

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(BlogAuthor::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_pivot', 'blog_id', 'tag_id');
    }
}
