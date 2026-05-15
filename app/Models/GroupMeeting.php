<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMeeting extends Model
{
    protected $fillable = [
        'group_id', 
        'meeting_date', 
        'notes', 
        'total_collected',
        'present_count',
        'absent_count',
        'apology_count',
        'guest_count'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'total_collected' => 'decimal:2',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances()
    {
        return $this->hasMany(GroupAttendance::class);
    }
}
