<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPaymentMethod extends Model
{
    protected $fillable = [
        'member_id',
        'type',
        'provider',
        'identifier',
        'label',
        'is_default',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
