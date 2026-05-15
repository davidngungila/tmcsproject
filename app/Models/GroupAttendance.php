<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAttendance extends Model
{
    protected $fillable = [
        'group_meeting_id', 'member_id', 'status', 'contribution_amount'
    ];

    protected $casts = [
        'contribution_amount' => 'decimal:2',
    ];

    public function meeting()
    {
        return $this->belongsTo(GroupMeeting::class, 'group_meeting_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
