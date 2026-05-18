<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['code', 'name', 'type', 'balance', 'is_active'];

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
