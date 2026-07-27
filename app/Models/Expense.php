<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'voucher_number',
        'category',
        'description',
        'amount',
        'expense_date',
        'payment_method',
        'reference_number',
        'attachment',
        'recorded_by',
        'status',
        'event_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
