<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    protected $table = 'event_attendance';

    protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];
}
