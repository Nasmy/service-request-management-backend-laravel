<?php

namespace App\Interfaces;

interface RolePermissionRepositoryInterface
{
    public function findPermissionsByRoleId($roleId): array;
    public function updatePermissionsByRoleId($roleId, $collection = []): array;
}
