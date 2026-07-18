<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutMdMessage extends Model
{
    protected $table = 'about_md_messages';

    protected $fillable = [
        'name',
        'designation',
        'photo',
        'title',
        'speech',
        'signature_image',
        'quotation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
