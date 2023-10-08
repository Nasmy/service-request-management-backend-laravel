<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermissionStoreRequest;
use App\Services\RolePermissionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public RolePermissionService $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */


    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/roles/{RoleId}/permissions",
     *      operationId="getRolesPermissionsList",
     *      tags={"RolesPermissions"},
     *      summary="Get list of  permissions by role ID",
     *      description="Returns list of permissions by role id ",
     *      security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="TenantId",
     *          description="Tenant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ), @OA\Parameter(
     *          name="RoleId",
     *          description="Role's ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully fetched role's permissions",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     * @throws Exception
     */

    public function index(int $id)
    {
        return $this->rolePermissionService->getPermissionsListByRoleId($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RolePermissionStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */


    /**
     * @OA\Post(
     *      path="/api/v1/{TenantId}/roles/{RoleId}/permissions",
     *      operationId="createRolesPermissions",
     *      tags={"RolesPermissions"},
     *      summary="Create permissions by role ID",
     *      description="Create permissions by role ID",
     *      security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="TenantId",
     *          description="Tenant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *        @OA\Parameter(
     *          name="RoleId",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *            type="object",
     *               required={"permissions"},
     *               @OA\Property(property="permissions", type="array",
     *                   @OA\Items(type="integer")
     *               ),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully created role's permissions",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     * @throws Exception
     */

    public function store(RolePermissionStoreRequest $request, int $id)
    {
        return $this->rolePermissionService->updatePermissionsByRoleId($id, $request->validated());
    }
}
