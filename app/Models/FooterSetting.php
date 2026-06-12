<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'description',
        'address',
        'phone',
        'email',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'pinterest_url',
        'copyright_text',
    ];
}
