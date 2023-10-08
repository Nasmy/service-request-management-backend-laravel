<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/roles",
     *      operationId="getRolesList",
     *      tags={"Roles"},
     *      summary="Get list of roles",
     *      description="Returns list of roles",
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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
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

    public function index(PaginatedRequest $request)
    {
        return $this->roleService->all($request->validated());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param RoleStoreRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post(
     *      path="/api/v1/{TenantId}/roles",
     *      operationId="createRole",
     *      tags={"Roles"},
     *      summary="Create role",
     *      description="Returns Created roles",
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
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *            type="object",
     *               required={"name", "ident","description", "permissions"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="ident", type="text"),
     *               @OA\Property(property="description", type="text"),
     *               @OA\Property(property="permissions", type="array",
     *                   @OA\Items(type="integer")
     *               ),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully created role",
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
    public function store(RoleStoreRequest $request)
    {
        return $this->roleService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/roles/{roleId}",
     *      operationId="getRolesDetail",
     *      tags={"Roles"},
     *      summary="Get Detail of role",
     *      description="Returns Detail of role",
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
     *      @OA\Parameter(
     *          name="roleId",
     *          description="roleId",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully fetched single role",
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
    public function show(int $id)
    {
        return $this->roleService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */

    /**
     * @OA\PUT(
     *      path="/api/v1/{TenantId}/roles/{roleId}",
     *      operationId="updateRole",
     *      tags={"Roles"},
     *      summary="Update role ",
     *      description="Returns update role ",
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
     *      @OA\Parameter(
     *          name="roleId",
     *          description="roleId",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *            type="object",
     *               required={"name", "ident","description", "permissions"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="ident", type="text"),
     *               @OA\Property(property="description", type="text"),
     *               @OA\Property(property="permissions", type="array",
     *                   @OA\Items(type="integer")
     *               ),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully updated role",
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
    public function update(RoleUpdateRequest $request, int $id): JsonResponse
    {
        return $this->roleService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    /**
     * @OA\Delete(
     *      path="/api/v1/{TenantId}/roles/{roleId}",
     *      operationId="deleteRole",
     *      tags={"Roles"},
     *      summary="Delete role",
     *      description="Delete role",
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
     *      @OA\Parameter(
     *          name="roleId",
     *          description="roleId",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully deleted role",
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
    public function destroy(int $id)
    {
        return $this->roleService->delete($id);
    }
}
