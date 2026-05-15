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
        'chairperson_id',
        'secretary_id',
        'accountant_id',
        'meeting_day',
        'regular_contribution_amount',
        'is_active',
        'formation_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'formation_date' => 'date',
        'is_active' => 'boolean',
        'regular_contribution_amount' => 'decimal:2',
    ];

    /**
     * Get the leader of the group.
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    /**
     * Get the chairperson of the group.
     */
    public function chairperson(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'chairperson_id');
    }

    /**
     * Get the secretary of the group.
     */
    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'secretary_id');
    }

    /**
     * Get the accountant of the group.
     */
    public function accountant(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'accountant_id');
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

    /**
     * Get the meetings of the group.
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(GroupMeeting::class);
    }

    /**
     * Get the plans of the group.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(GroupPlan::class);
    }

    /**
     * Get the message templates for the group.
     */
    public function messageTemplates(): HasMany
    {
        return $this->hasMany(MessageTemplate::class);
    }

    /**
     * Get the scheduled messages for the group.
     */
    public function scheduledMessages(): HasMany
    {
        return $this->hasMany(GroupScheduledMessage::class);
    }
}
