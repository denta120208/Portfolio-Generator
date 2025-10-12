<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioImage extends Model
{
    protected $fillable = [
        'portfolio_id',
        'image_path',
        'title',
        'description',
        'is_main',
        'sort_order'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
