<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    protected $table = 'blog_authors';

    protected $fillable = [
        'name', 'profile_photo', 'designation', 'biography', 'email', 'website',
        'facebook_url', 'linkedin_url', 'twitter_url', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }
}
