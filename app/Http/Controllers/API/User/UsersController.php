<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Requests\User\UserPasswordUpdateRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{

    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/users",
     *      operationId="getUsersList",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
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
     *          description="Successfully fetched users list",
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
        return $this->userService->all($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    /**
     * @OA\Post(
     *      path="/api/v1/{TenantId}/users",
     *      operationId="createUser",
     *      tags={"Users"},
     *      summary="Create user",
     *      description="Returns created user",
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
     *    @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *             type="object",
     *               required={"first_name", "last_name","email", "mobile","username", "password", "password_confirmation","city", "address","zip"},
     *               @OA\Property(property="first_name", type="text"),
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
     *          description="Successfully created user",
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

    public function store(UserStoreRequest $request)
    {
        return $this->userService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */


    /**
     * @OA\Get(
     *      path="/api/v1/{TenantId}/users/{userId}",
     *      operationId="getUserDetail",
     *      tags={"Users"},
     *      summary="Get detail of user",
     *      description="Returns detail of user",
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
     *    @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully fetched single user detail",
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
        return $this->userService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|User $request
     * @param  int  $id
     * @return Response
     */

    /**
     * @OA\Patch(
     *      path="/api/v1/{TenantId}/users/{userId}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update user",
     *      description="Returns update user",
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
     *    @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *             type="object",
     *               required={"first_name", "last_name","email", "mobile"},
     *               @OA\Property(property="first_name", type="text"),
     *               @OA\Property(property="last_name", type="text"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="mobile", type="text"),
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
     *          description="Successfully updated user",
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

    public function update(UserUpdateRequest $request, int $id)
    {
        return $this->userService->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */


    /**
     * @OA\Delete(
     *      path="/api/v1/{TenantId}/users/{userId}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="delete user",
     *      description="delete user",
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
     *    @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully deleted user",
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

    public function destroy($id)
    {
        return $this->userService->delete($id);
    }

    /**
     * Update the specified resource.
     *
     * @param UserPasswordUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function updatePassword(UserPasswordUpdateRequest $request, User $user)
    {
        return $this->userService->update($user->id, $request->validated());
    }
}
