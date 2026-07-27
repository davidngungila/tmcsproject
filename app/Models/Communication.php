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
        'recipients',
        'error_message'
    ];

    protected $casts = [
        'criteria' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
