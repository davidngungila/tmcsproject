<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reconciliation extends Model
{
    protected $fillable = [
        'reference_id',
        'period_start',
        'period_end',
        'opening_balance',
        'closing_balance',
        'total_income',
        'total_expenses',
        'difference',
        'notes',
        'status',
        'reconciled_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function reconciler()
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }
}
