<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutChairmanMessage extends Model
{
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
