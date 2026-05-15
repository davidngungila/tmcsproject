<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScheduledMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'title',
        'message',
        'scheduled_at',
        'frequency',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
