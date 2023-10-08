<?php

namespace App\Services;

use App\Interfaces\RolePermissionRepositoryInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RolePermissionService
{
    use ApiResponse;

    public RolePermissionRepositoryInterface $rolePermissionRepository;

    public function __construct(RolePermissionRepositoryInterface $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    /**
     * @throws Exception
     */
    public function getPermissionsListByRoleId($roleId): JsonResponse
    {
        try {
            $permissionList = $this->rolePermissionRepository->findPermissionsByRoleId($roleId);
            return $this->sendResponse($permissionList, "List of permissions for role $roleId", 200);
        } catch (Exception $e) {
            Log::error("Retrieved all permissions for role $roleId is error : WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function updatePermissionsByRoleId($roleId, ?array $permissions): JsonResponse
    {
        try {
            $permissionsList = $this->rolePermissionRepository->updatePermissionsByRoleId($roleId, $permissions);
            Log::info("Permissions updated for this roleId $roleId : WEB ".json_encode($permissions));

            return $this->sendResponse($permissionsList, "Permissions updated for role $roleId", 201);
        }catch (Exception $e) {
            Log::info("Permissions update error $roleId : WEB ".json_encode($permissions));
            throw $e;
        }
    }
}
