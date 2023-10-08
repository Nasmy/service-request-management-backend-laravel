<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    use ApiResponse;

    public PermissionRepositoryInterface $permissionRepository;

    const PERMISSION_LIST = "index";
    const PERMISSION_SEARCH = "search";
    const PERMISSION_GLOBAL_SEARCH = "globalSearch";
    const PERMISSION_VIEW = "show";
    const PERMISSION_VIEW_ALL = "showAll";
    const PERMISSION_CREATE = "store";
    const PERMISSION_EDIT = "update";
    const PERMISSION_DELETE = "destroy";
    const PERMISSION_APPROVE = "approve";

    const PARENT_PERMISSION = 'permissions';

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @throws Exception
     */
    public function getPermissionList(): JsonResponse
    {
        try {
            $permissionList = $this->permissionRepository->all();
            return $this->sendResponse($permissionList, 'List of permissions', 200);
        } catch (Exception $e) {
            Log::error("Retrieved all permissions is error : WEB ");
            throw $e;
        }
    }

    public static function concatPermission($parent, $child): ?string
    {
        if ($child === self::PERMISSION_LIST
            || $child === self::PERMISSION_SEARCH
            || stripos($child, self::PERMISSION_GLOBAL_SEARCH) !== false) {
            $child = self::PERMISSION_VIEW;
        }
        return ($parent != null && $child != null) ? $parent . "." . $child : null;
    }

    public static function getPermissions(): array
    {
        return [
            [
                "name" => "User Management",
                "parent" => RoleService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE, self::PERMISSION_APPROVE],
                "description" => "Manage Roles",
                "active" => 1
            ],

            [
                "name" => "User Management",
                "parent" => self::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW],
                "description" => "Manage Permissions",
                "active" => 1
            ],

            [
                "name" => "User Management",
                "parent" => UserService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE],
                "description" => "Manage Users",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => DepartmentService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE],
                "description" => "Manage Department",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => JobService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_VIEW_ALL, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE, self::PERMISSION_APPROVE],
                "description" => "Jobs",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => CitizenService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE],
                "description" => "Citizens",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => DocumentService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE, self::PERMISSION_APPROVE],
                "description" => "Documents",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => OrganizationService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_LIST, self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE],
                "description" => "Organizations",
                "active" => 1
            ],

            [
                "name" => "Job Management",
                "parent" => ContactService::PARENT_PERMISSION,
                "child" => [self::PERMISSION_LIST, self::PERMISSION_VIEW, self::PERMISSION_CREATE, self::PERMISSION_EDIT, self::PERMISSION_DELETE, self::PERMISSION_APPROVE],
                "description" => "Contacts",
                "active" => 1
            ],
        ];
    }
}
