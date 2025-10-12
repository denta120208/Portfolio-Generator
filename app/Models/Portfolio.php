<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'project_name',
        'project_title',
        'description',
        'template_id',
        'custom_data',
        'status',
        'user_id',
        // Personal Information
        'full_name',
        'email',
        'phone',
        'linkedin',
        'github',
        'website',
        'about_me',
        'education',
        'experience',
        'skills',
        'certifications',
        'certificate_image'
    ];

    protected $casts = [
        'additional_images' => 'array',
        'custom_data' => 'array',
        'certificate_images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(PortfolioTemplate::class, 'template_id');
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(PortfolioImage::class)->where('is_main', true);
    }
}
