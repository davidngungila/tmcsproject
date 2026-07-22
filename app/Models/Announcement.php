<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'target_audience',
        'expiry_date',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now());
                    });
    }

    public function scopeForAudience($query, $audience)
    {
        return $query->where('target_audience', $audience)
                    ->orWhere('target_audience', 'all');
    }
}
