<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'phone',
        'email',
        'preferred_date',
        'preferred_time',
        'note',
        'status',
        'admin_note',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
