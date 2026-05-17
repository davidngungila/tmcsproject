<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'duration',
        'delivery_mode',
        'session',
        'is_active',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
