<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberFinancial extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'month',
        'savings',
        'loans',
        'collections'
    ];

    protected $casts = [
        'savings' => 'decimal:2',
        'loans' => 'decimal:2',
        'collections' => 'decimal:2'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
