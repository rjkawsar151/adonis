<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'main_description',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'main_image',
        'cause_title',
        'cause_description',
        'cause_image',
        'treatment_title',
        'treatment_description',
        'treatment_image',
        'is_featured',
        'show_in_sidebar',
        'status',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'show_in_sidebar' => 'boolean',
    ];

    public function bullets()
    {
        return $this->hasMany(ServiceBullet::class)->orderBy('sort_order');
    }

    public function conditionBullets()
    {
        return $this->bullets()->where('section_type', 'condition');
    }

    public function causeBullets()
    {
        return $this->bullets()->where('section_type', 'cause');
    }

    public function treatmentBullets()
    {
        return $this->bullets()->where('section_type', 'treatment');
    }

    public function steps()
    {
        return $this->hasMany(ServiceStep::class)->orderBy('sort_order');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
