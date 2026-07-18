<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $fillable = [
        'name', 'slug', 'description', 'featured_image', 'seo_title', 'meta_description', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
