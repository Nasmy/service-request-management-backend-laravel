<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TenantStoreRequest;
use App\Services\TenantService;
use Exception;
use Illuminate\Http\JsonResponse;

class TenantController extends ApiController
{

    public TenantService $tenantService;


    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }


    /**
     * @OA\POST(
     *      path="/api/tenant/register",
     *      operationId="storeTenant",
     *      tags={"Tenants"},
     *      summary="Create Tenants",
     *      description="Create Tenants",
     *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *            type="object",
     *               required={"first_name", "last_name","email", "mobile","username", "password", "password_confirmation","city", "address","zip"},
     *                    @OA\Property(property="first_name", type="text"),
     *               @OA\Property(property="last_name", type="text"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="mobile", type="text"),
     *               @OA\Property(property="username", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password"),
     *               @OA\Property(property="city", type="text"),
     *               @OA\Property(property="address", type="text"),
     *               @OA\Property(property="zip", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully created tenant",
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

    public function store(TenantStoreRequest $request): JsonResponse
    {
        $request->validated();
        $data = $request->all();

        $res = $this->tenantService->createTenant($data);

        if (isset($res['is_created']) && $res['is_created']) {
            return $this->sendResponse($res, "successfully registered", 200);
        } else {
            return $this->sendError($res, 401);
        }
    }
}
