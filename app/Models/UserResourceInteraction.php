<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserResourceInteraction extends Model
{
    protected $fillable = [
        'user_id', 'resource_id', 'is_bookmarked', 
        'last_page_read', 'last_viewed_at', 'personal_notes'
    ];

    protected $casts = [
        'is_bookmarked' => 'boolean',
        'last_viewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
