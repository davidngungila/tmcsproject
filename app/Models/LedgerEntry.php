<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'account_id', 
        'transaction_date', 
        'description', 
        'debit', 
        'credit', 
        'reference_type', 
        'reference_id', 
        'recorded_by'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
