<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public PermissionService $pemissionService;


    public function __construct(PermissionService $pemissionService)
    {
        $this->pemissionService = $pemissionService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/permission/list",
     *      operationId="getPermissionsList",
     *      tags={"Permissions"},
     *      summary="Get list of permissions",
     *      description="Returns list of permissions",
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
     *          description="Successfully fetched permissions list",
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


    public function index()
    {
        return $this->pemissionService->getPermissionList();
    }
}
