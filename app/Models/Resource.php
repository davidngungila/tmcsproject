<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'file_path', 
        'file_type', 'file_size', 'page_count', 'is_featured', 
        'download_count', 'view_count', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    public function interactions()
    {
        return $this->hasMany(UserResourceInteraction::class);
    }
}
