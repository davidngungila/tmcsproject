<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'gl_account',
        'min_amount',
        'is_mandatory',
        'frequency',
        'color',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_mandatory' => 'boolean',
        'min_amount' => 'decimal:2',
    ];

    /**
     * Get the contributions for this type.
     */
    public function contributions()
    {
        // Using name as the key for now since the existing contributions table uses string contribution_type
        return $this->hasMany(Contribution::class, 'contribution_type', 'name');
    }
}
