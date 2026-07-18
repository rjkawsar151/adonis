<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    protected $table = 'blog_tags';

    protected $fillable = [
        'name', 'slug', 'description', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function posts()
    {
        return $this->belongsToMany(Blog::class, 'blog_tag_pivot', 'tag_id', 'blog_id');
    }
}
