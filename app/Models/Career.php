<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'short_description', 'description', 'responsibilities',
        'educational_requirements', 'experience_requirements', 'additional_requirements',
        'skills', 'benefits', 'department_id', 'employment_type_id', 'location', 'gender',
        'vacancy', 'salary_min', 'salary_max', 'salary_type', 'application_deadline',
        'featured_image', 'status', 'is_featured', 'seo_title', 'seo_description',
        'created_by', 'updated_by',
        'show_address', 'show_linkedin', 'show_portfolio', 'show_current_company',
        'show_current_designation', 'show_expected_salary', 'show_joining_date', 'show_cover_letter'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'application_deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'show_address' => 'boolean',
        'show_linkedin' => 'boolean',
        'show_portfolio' => 'boolean',
        'show_current_company' => 'boolean',
        'show_current_designation' => 'boolean',
        'show_expected_salary' => 'boolean',
        'show_joining_date' => 'boolean',
        'show_cover_letter' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function questions()
    {
        return $this->hasMany(CareerQuestion::class)->orderBy('sort_order');
    }

    public function applications()
    {
        return $this->hasMany(CareerApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now()->toDateString());
            });
    }
}
