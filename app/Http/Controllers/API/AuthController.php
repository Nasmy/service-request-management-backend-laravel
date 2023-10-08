<?php

namespace App\Http\Controllers\API;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

     /**
     * @OA\Post(
     *      path="/api/v1/{TenantId}/auth",
     *      operationId="AuthenticationLogin",
     *      tags={"Authentication"},
     *      summary="User login",
     *      description="user logout",
     *      security={ {"basicAuth": {} }},
     *      @OA\Parameter(
     *          name="TenantId",
     *          description="Tenant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="PHP_AUTH_USER",
     *          description="User Name",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ), @OA\Parameter(
     *          name="PHP_AUTH_PW",
     *          description="Password",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Logged in Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Logged in Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */



    public function login(): JsonResponse
    {
        return $this->authService->restUserLogin();
    }

    /**
     * @OA\Post(
     *      path="/api/v1/{TenantId}/logout",
     *      operationId="Authentication",
     *      tags={"Authentication"},
     *      summary="User log out",
     *      description="user logout",
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
     *          response=201,
     *          description="Log Out Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Log Out Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */


    public function logout(Request $request)
    {
        return $this->authService->restUserLogout($request->user());
    }
}
