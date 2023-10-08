<?php

use App\Http\Controllers\API;
use App\Http\Controllers\API\CitizenController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\Role\RoleController;
use App\Http\Controllers\API\Role\RolePermissionController;
use App\Http\Controllers\API\User\UsersController;
use App\Services\OrganizationService;
use App\Services\CitizenService;
use App\Services\JobService;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\JobController;
use App\Services\ContactService;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Services\UserService;
use App\Services\RoleService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** Tenant Api Routes */
Route::post("tenant/register", [API\TenantController::class, 'store'])->name('tenant.store');

$api_version = config('app.api_version');
$tenant_api_path = isset($api_version) ? config('app.api_version') . '/{tenant}' : '{tenant}';

Route::group([
    'prefix' => '/' . $tenant_api_path,
    'middleware' => [InitializeTenancyByPath::class, 'auth.basic:,username'],
], function () {
    Route::post('auth', [API\AuthController::class, 'login']);
});


Route::group([
    'prefix' => '/' . $tenant_api_path,
    'middleware' => [InitializeTenancyByPath::class, 'auth:sanctum'],
], function () {
    Route::post('logout', [API\AuthController::class, 'logout']);
    Route::apiResource('/permissions', PermissionController::class)->only(['index']);
    Route::put('/users/{user}/password', [UsersController::class, 'updatePassword'])->middleware('authorization:' . UserService::PARENT_PERMISSION);
    Route::apiResource('/users', UsersController::class)->middleware('authorization:' . UserService::PARENT_PERMISSION);
    Route::apiResource('/roles', RoleController::class)->middleware('authorization:' . RoleService::PARENT_PERMISSION);
    Route::apiResource('/roles.permissions', RolePermissionController::class)->only(['index', 'store']);
    Route::get('/citizens/search', [CitizenController::class, 'search'])->middleware('authorization:' . CitizenService::PARENT_PERMISSION);
    Route::get('/citizens/globalSearch', [CitizenController::class, 'globalSearch'])->middleware('authorization:' . CitizenService::PARENT_PERMISSION);
    Route::apiResource('/citizens', CitizenController::class)->middleware('authorization:' . CitizenService::PARENT_PERMISSION);
    Route::get('/jobs/search', [JobController::class, 'search'])->middleware('authorization:' . JobService::PARENT_PERMISSION);
    Route::get('/jobs/{status}/globalSearch', [JobController::class, 'statusGlobalSearch'])->middleware('authorization:' . JobService::PARENT_PERMISSION)
    ->where('status', 'todo|in_progress|completed|archived');
    Route::get('/jobs/globalSearch', [JobController::class, 'globalSearch'])->middleware('authorization:' . JobService::PARENT_PERMISSION);
    Route::apiResource('/jobs', JobController::class)->middleware('authorization:' . JobService::PARENT_PERMISSION);
    Route::get('/organizations/search', [OrganizationController::class, 'search'])->middleware('authorization:' . OrganizationService::PARENT_PERMISSION);
    Route::apiResource('/organizations', OrganizationController::class)->middleware('authorization:' . OrganizationService::PARENT_PERMISSION);
    Route::get('/contacts/search', [ContactController::class, 'search'])->middleware('authorization:' . ContactService::PARENT_PERMISSION);
    Route::get('/contacts/globalSearch', [ContactController::class, 'globalSearch'])->middleware('authorization:' . ContactService::PARENT_PERMISSION);
    Route::apiResource('/contacts', ContactController::class)->middleware('authorization:' . ContactService::PARENT_PERMISSION);
    Route::apiResource('/documents', DocumentController::class)->middleware('authorization:' . DocumentService::PARENT_PERMISSION);
});
