<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'career_id', 'reference_number', 'full_name', 'email', 'phone',
        'present_address', 'linkedin_url', 'portfolio_url', 'current_company',
        'current_designation', 'expected_salary', 'available_joining_date',
        'cover_letter', 'cv_path', 'status', 'admin_note', 'submitted_ip'
    ];

    protected $casts = [
        'available_joining_date' => 'date',
        'expected_salary' => 'decimal:2'
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function answers()
    {
        return $this->hasMany(CareerApplicationAnswer::class);
    }

    public function histories()
    {
        return $this->hasMany(CareerApplicationStatusHistory::class);
    }
}
