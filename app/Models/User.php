<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends CrudModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'organization',
            'first_name',
            'last_name',
            'email',
            'mobile',
            'city',
            'zip',
            'address',
            'role_id',
            'password',
            'is_admin',
            'username',
        ]);
    }

    public function getHidden(): array
    {
        return array_merge(parent::getHidden(), [
            'password',
            'role_id',
            'is_admin',
            'remember_token',
            'email_verified_at',
        ]);
    }

    public function getCasts(): array
    {
        return array_merge($this->casts, [
            'is_admin' => 'boolean',
            'email_verified_at' => 'datetime:Y-m-d - H:i:s'
        ]);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class);
    }

    public function createdCitizens(): HasMany
    {
        return $this->hasMany(Citizen::class, 'created_by');
    }

    public function updatedCitizens(): HasMany
    {
        return $this->hasMany(Citizen::class, 'updated_by');
    }

    public function createdJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'created_by');
    }

    public function updatedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'updated_by');
    }

    public function assignedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'user_id');
    }

    public function createdRoles(): HasMany
    {
        return $this->hasMany(Role::class, 'created_by');
    }

    public function updatedRoles(): HasMany
    {
        return $this->hasMany(Role::class, 'updated_by');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function updatedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'updated_by');
    }
}
