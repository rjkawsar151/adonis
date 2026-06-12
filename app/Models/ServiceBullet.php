<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBullet extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'section_type',
        'bullet_text',
        'sort_order',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
