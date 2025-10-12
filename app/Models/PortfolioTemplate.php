<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'template_html',
        'template_css',
        'template_config',
        'is_active'
    ];

    protected $casts = [
        'template_config' => 'array'
    ];

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'template_id');
    }
}
