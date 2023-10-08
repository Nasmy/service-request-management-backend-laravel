<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthService
{
    use ApiResponse;

    const AUTH_ADMIN = 1;
    const TOKEN_API = "rest_auth_token";

    public UserRepository $userRepository;
    public RoleRepository $roleRepository;
    public PermissionRepository $permissionRepository;
    public RolePermissionRepository $rolePermissionRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, PermissionRepository $permissionRepository, RolePermissionRepository $rolePermissionRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }


    public function getUserId()
    {
        return Auth::user();
    }

    public function restUserLogin(): JsonResponse
    {
        $user = Auth::user();
        $permissions = $this->rolePermissionRepository->getAllPermissionsIdentificationsByRoleId($user->role_id); // returning all permissions identifications
        $response['token'] = self::generateAuthToken($user, self::TOKEN_API);
        $response['user'] = $user;
        if ($user->role != null)
            $user->role->get();
        if ($user->createdBy != null)
            $user->createdBy->get();
        if ($user->updatedBy != null)
            $user->updatedBy->get();
        $response['permissions'] = $permissions;
        Log::info("User logged in successfully : API " . json_encode($user));
        return $this->sendResponse($response, "Successfully logged in");
    }

    public function restUserLogout(User $user): JsonResponse
    {
        $user->currentAccessToken()->delete();
        Log::info("User logged out successfully : API " . json_encode($user));
        return $this->sendResponse(null, 'Successfully logged out');
    }

    public function webUserLogin($authAttempt)
    {
        if (Auth::attempt($authAttempt)) {
            $user = Auth::user();
            Auth::login($user);
            Log::info("User logged in successfully : WEB " . json_encode($user));
            return redirect(route('dashboard.index'));
        } else {
            Log::error("Invalid credentials in user login : WEB " . json_encode($authAttempt));
            return back()->withErrors(['Invalid credentials!'])->setStatusCode(401);
        }
    }


    /**
     * @param $user
     * @param $token
     * @return string
     */

    public static function generateAuthToken($user, $token): string
    {
        return $user->createToken($token)->plainTextToken;
    }

    /**
     * @param $roleId
     * @param $permission
     * @return bool
     */
    public function checkAuthorize($roleId, $permission): bool
    {
        if ($this->isAdmin($roleId)) { // initially check the role is Admin
            return true;
        }

        // If current user id is not an admin check with current user role with the permissions
        $permissionData = $this->permissionRepository->findByIdent($permission);
        if ($permissionData == null || $permissionData->count() <= 0) {
            return false;
        }

        $permissionId = $permissionData->id;
        $role = $this->roleRepository->findById($roleId);

        $rolePermission = $role->permissions()->where('permission_id', $permissionId)->get();

        if (count($rolePermission) >= 1) {
            return true;
        }

        return false;
    }


    /**
     * @param $roleId
     * @return bool
     */
    public function isAdmin($roleId): bool
    {
        if ($roleId == Role::DEFAULT_ADMIN_ROLE_ID) {
            return true;
        }
        return false;
    }

}
