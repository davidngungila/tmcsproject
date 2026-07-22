<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'subject', 
        'message', 
        'type', 
        'recipient_type', 
        'group_id', 
        'member_id', 
        'criteria', 
        'sent_by', 
        'status', 
        'sent_at',
        'scheduled_at',
        'recipients'
    ];

    protected $casts = [
        'criteria' => 'array',
    ];
}
