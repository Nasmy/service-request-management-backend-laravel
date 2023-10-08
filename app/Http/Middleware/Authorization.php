<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Route;

class Authorization
{
    // use ApiResponse;


    public $authService;
    public $roleService;

    public function __construct(AuthService $authService,RoleService $roleService)
    {
        $this->authService = $authService;
        $this->roleService = $roleService;
    }


    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @param null $parentPermission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $parentPermission = null)
    {
        $role_id = $request->user()->role_id;
        $childPermission = Route::getCurrentRoute()->getActionMethod();
        $permission = PermissionService::concatPermission($parentPermission, $childPermission);
        $isAuthorize = $this->authService->checkAuthorize($role_id, $permission);
        if ($isAuthorize) {
            return $next($request);
        } else {
            return abort(403, "You don't have permissions to access ");
        }

    }
}
