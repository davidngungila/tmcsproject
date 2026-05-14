<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'leader_id',
        'is_active',
        'formation_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'formation_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the leader of the group.
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    /**
     * Get the user who created the group.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the group.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the members that belong to the group.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_groups')
            ->withPivot(['join_date', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Get the communications sent to the group.
     */
    public function communications(): HasMany
    {
        return $this->hasMany(Communication::class);
    }
}
