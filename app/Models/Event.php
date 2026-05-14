<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'event_time',
        'venue',
        'max_capacity',
        'status',
        'photo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'max_capacity' => 'integer',
    ];

    /**
     * Get the user who created the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the event.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the event attendance records.
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }
}
