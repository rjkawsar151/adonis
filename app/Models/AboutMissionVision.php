<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutMissionVision extends Model
{
    use SoftDeletes;

    protected $table = 'about_missions_visions';

    protected $fillable = [
        'type',
        'title',
        'short_description',
        'content',
        'icon_or_image',
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
