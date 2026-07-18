<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerQuestion extends Model
{
    protected $fillable = [
        'career_id', 'question', 'help_text', 'question_type', 'options',
        'is_required', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
