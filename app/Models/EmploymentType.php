<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'status'];

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
