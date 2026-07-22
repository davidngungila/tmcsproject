<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    protected $fillable = [
        'receipt_number',
        'member_id',
        'amount',
        'contribution_type',
        'payment_method',
        'payment_phone',
        'contribution_date',
        'transaction_reference',
        'feedtan_order_reference',
        'feedtan_transaction_id',
        'feedtan_payment_method',
        'feedtan_status',
        'feedtan_paid_at',
        'notes',
        'receipt_qr_code',
        'is_verified',
        'recorded_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'contribution_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the member who made the contribution.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who recorded the contribution.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the contribution type details.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ContributionType::class, 'contribution_type', 'name');
    }

    /**
     * Get the user who verified the contribution.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
