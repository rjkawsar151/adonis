<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutCompanyIntroduction extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'featured_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
