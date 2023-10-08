<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class RoleService
{
    use ApiResponse;

    public RoleRepository $roleRepository;

    const PARENT_PERMISSION = 'roles';

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $roleList = $this->roleRepository->all($params);
            Log::info("Retrieved all roles : WEB ");
            $functionName = $roleList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($roleList, 'List of roles', 200);
        } catch (Exception $e) {
            Log::error("Retrieved all roles is error : WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {
        try {
            $role = $this->roleRepository->findById($id);
            Log::info("Retrieved single roles by id:$id: WEB ");
            return $this->sendResponse($role, "Details of role #$role->id", 200);
        } catch (Exception $e) {
            Log::error("Error on Retrieve single roles by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $userData): JsonResponse
    {
        try {
            $userData['active'] = true;
            $role = $this->roleRepository->createOrUpdate($userData);
            Log::info("role created: WEB ".json_encode($role));
            return $this->sendResponse($role, "Role #$role->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on role create : WEB ".json_encode($userData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update($userData, int $id): JsonResponse
    {
        try {
            $role = $this->roleRepository->createOrUpdate($userData, $id);
            Log::info("role updated: WEB ".json_encode($role));
            return $this->sendResponse($role, "Role #$role->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on role update : WEB ".json_encode($userData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->roleRepository->delete($id);
            Log::info("deleted roles by id:$id: WEB ");
            return $this->sendResponse(null, "Role #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete roles by id:$id: WEB ");
            throw $e;
        }
    }
}
