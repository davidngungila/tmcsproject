<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider_type',
        'api_key',
        'api_secret',
        'api_endpoint',
        'sender_id',
        'is_active',
        'extra_config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'extra_config' => 'json'
    ];
}
