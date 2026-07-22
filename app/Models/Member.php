<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'registration_number',
        'full_name',
        'email',
        'phone',
        'member_type',
        'category_id',
        'program_id',
        'date_of_birth',
        'address',
        'baptismal_name',
        'gender',
        'parish',
        'diocese',
        'region',
        'photo',
        'qr_code',
        'is_active',
        'profile_completed',
        'registration_date',
        'created_by',
        'updated_by',
        'parent_id',
        'relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
        'is_active' => 'boolean',
        'profile_completed' => 'boolean',
    ];

    /**
     * Get the age of the member.
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Get the user linked to this member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the member.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MemberCategory::class, 'category_id');
    }

    /**
     * Get the program of the member.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Get the parent (head of family) of this member.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'parent_id');
    }

    /**
     * Get the children and spouse members of this family head.
     */
    public function familyMembers(): HasMany
    {
        return $this->hasMany(Member::class, 'parent_id');
    }

    /**
     * Get the user who created the member.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the member.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the contributions for the member.
     */
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function savedPaymentMethods()
    {
        return $this->hasMany(SavedPaymentMethod::class);
    }



    /**
     * Get the groups that belong to the member.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'member_groups')
            ->withPivot(['join_date', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Get the groups where the member is the chairperson.
     */
    public function chairpersonGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'chairperson_id');
    }

    /**
     * Get the groups where the member is the secretary.
     */
    public function secretaryGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'secretary_id');
    }

    /**
     * Get the groups where the member is the accountant.
     */
    public function accountantGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'accountant_id');
    }

    /**
     * Get the groups where the member is a leader (chairperson, secretary, or accountant).
     */
    public function leadingGroups(): HasMany
    {
        return $this->chairpersonGroups()->orWhere('secretary_id', $this->id)->orWhere('accountant_id', $this->id);
    }

    /**
     * Get the event attendance for the member.
     */
    public function eventAttendance(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    /**
     * Get the election candidates for the member.
     */
    public function electionCandidates(): HasMany
    {
        return $this->hasMany(ElectionCandidate::class);
    }

    /**
     * Get the election votes for the member.
     */
    public function electionVotes(): HasMany
    {
        return $this->hasMany(ElectionVote::class, 'voter_id');
    }

    /**
     * Get the assets assigned to the member.
     */
    public function assignedAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_to');
    }

    /**
     * Get the asset history for the member.
     */
    public function assetHistory(): HasMany
    {
        return $this->hasMany(AssetHistory::class, 'assigned_to');
    }

    /**
     * Get the shop sales for the member.
     */
    public function shopSales(): HasMany
    {
        return $this->hasMany(ShopSale::class);
    }

    /**
     * Get the financial records for the member.
     */
    public function financials(): HasMany
    {
        return $this->hasMany(MemberFinancial::class);
    }
}
