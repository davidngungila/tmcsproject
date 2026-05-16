<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'profile_image', 'is_active', 'last_login_at', 'last_login_ip', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 'force_password_change', 'login_attempts', 'locked_until'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'force_password_change' => 'boolean',
            'locked_until' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Get the authentication logs for the user.
     */
    public function authenticationLogs()
    {
        return $this->hasMany(AuthenticationLog::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission($permissionName)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Get the member details for this user.
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Get the roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get the members created by the user.
     */
    public function createdMembers()
    {
        return $this->hasMany(Member::class, 'created_by');
    }

    /**
     * Get the members updated by the user.
     */
    public function updatedMembers()
    {
        return $this->hasMany(Member::class, 'updated_by');
    }

    /**
     * Get the contributions recorded by the user.
     */
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'recorded_by');
    }

    /**
     * Get the certificates issued by the user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'issued_by');
    }

    /**
     * Get the events created by the user.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get the elections created by the user.
     */
    public function elections()
    {
        return $this->hasMany(Election::class, 'created_by');
    }

    /**
     * Get the assets created by the user.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    /**
     * Get the shop products created by the user.
     */
    public function shopProducts()
    {
        return $this->hasMany(ShopProduct::class, 'created_by');
    }

    /**
     * Get the shop sales processed by the user.
     */
    public function shopSales()
    {
        return $this->hasMany(ShopSale::class, 'sold_by');
    }

    /**
     * Get the communications sent by the user.
     */
    public function communications()
    {
        return $this->hasMany(Communication::class, 'sent_by');
    }
}
