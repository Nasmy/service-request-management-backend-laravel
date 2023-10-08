<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends CrudModel
{
    use HasFactory;

    //admin
    const DEFAULT_ROLE = "administrator";
    const DEFAULT_ADMIN_ROLE_ID = 1;
    //tenant
    const TENANT_ROLE = "tenant";
    const DEFAULT_TENANT_ROLE_ID = 2;
    //user
    const USER_ROLE = "user";
    const DEFAULT_USER_ROLE_ID = 3;

    protected $table = 'roles';

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'name',
            'ident',
            'description',
            'active'
        ]);
    }

    public function getCasts(): array
    {
        return array_merge($this->casts, [
            'active' => 'bool'
        ]);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

}
