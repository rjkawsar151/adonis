<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'question',
        'answer',
        'sort_order',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
