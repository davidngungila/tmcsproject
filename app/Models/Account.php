<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'code', 
        'name', 
        'bank_name', 
        'account_number', 
        'branch_code', 
        'type', 
        'balance', 
        'is_active',
        'is_default_income'
    ];

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
