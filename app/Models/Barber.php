<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    protected $table = 'barbers';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'experienceYears', 'specialty', 'portraitUrl', 'bio', 'rating',
    ];

    protected $casts = [
        'experienceYears' => 'integer',
        'rating' => 'float',
    ];
}
