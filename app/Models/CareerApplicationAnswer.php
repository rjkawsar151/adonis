<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplicationAnswer extends Model
{
    protected $fillable = [
        'career_application_id', 'career_question_id', 'question_snapshot', 'answer', 'file_path'
    ];

    protected $casts = [
        'question_snapshot' => 'array'
    ];

    public function application()
    {
        return $this->belongsTo(CareerApplication::class, 'career_application_id');
    }

    public function question()
    {
        return $this->belongsTo(CareerQuestion::class, 'career_question_id');
    }
}
