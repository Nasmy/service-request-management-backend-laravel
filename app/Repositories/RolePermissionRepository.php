<?php

namespace App\Repositories;

use App\Interfaces\RolePermissionRepositoryInterface;
use App\Models\Role;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{

    public function getAllPermissionsIdentificationsByRoleId($roleId): array
    {
        return collect(Role::where(['id' => $roleId])->firstOrFail()->permissions->toArray())->pluck('ident')->toArray();
    }

    public function findPermissionsByRoleId($roleId): array
    {
        return Role::where(['id' => $roleId])->firstOrFail()->permissions->all();
    }

    public function updatePermissionsByRoleId($roleId, $collection = []): array
    {
        $role = Role::where(['id' => $roleId])->firstOrFail();
        $role->permissions()->sync($collection['permissions']);
        return $role->permissions->all();
    }
}
