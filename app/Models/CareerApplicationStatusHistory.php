<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplicationStatusHistory extends Model
{
    protected $table = 'career_application_status_histories';

    protected $fillable = [
        'career_application_id', 'previous_status', 'new_status', 'changed_by', 'note'
    ];

    public function application()
    {
        return $this->belongsTo(CareerApplication::class, 'career_application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
