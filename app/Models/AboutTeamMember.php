<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutTeamMember extends Model
{
    use SoftDeletes;

    protected $table = 'about_team_members';

    protected $fillable = [
        'name',
        'designation',
        'photo',
        'biography',
        'facebook_url',
        'linkedin_url',
        'email',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
