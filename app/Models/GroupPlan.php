<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPlan extends Model
{
    protected $fillable = [
        'group_id', 'title', 'description', 'budget_amount', 
        'start_date', 'end_date', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget_amount' => 'decimal:2',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
