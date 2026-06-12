<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'step_number',
        'title',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
