<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutCta extends Model
{
    protected $table = 'about_ctas';

    protected $fillable = [
        'title',
        'description',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'background_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
