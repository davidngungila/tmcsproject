<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionVote extends Model
{
    protected $fillable = [
        'election_id',
        'voter_id',
        'candidate_id',
        'position',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Get the election.
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the member who voted.
     */
    public function voter(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'voter_id');
    }

    /**
     * Get the candidate who received the vote.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(ElectionCandidate::class);
    }
}
