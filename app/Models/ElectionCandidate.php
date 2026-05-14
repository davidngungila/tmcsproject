<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionCandidate extends Model
{
    protected $fillable = [
        'election_id',
        'member_id',
        'position',
        'manifesto',
        'status',
    ];

    /**
     * Get the election that the candidate is participating in.
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the member who is the candidate.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
