<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    use ApiResponse;

    public UserRepository $userRepository;

    const  NOT_ADMIN = 0;

    const PARENT_PERMISSION = 'users';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @description convert password to hash
     * @param $password
     * @return string
     */
    public static function hashPassword($password): string
    {
        return Hash::make($password);
    }


    /**
     * @description retrive all users
     * @param $params
     * @return JsonResponse
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $usersList = $this->userRepository->all($params);
            $functionName = $usersList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($usersList, 'List of Users', 200);
        } catch (Exception $e) {
            Log::error("Retrieved all users is error : WEB ");
            throw $e;
        }
    }


    /**
     * @description create user
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    public function create(array $data): JsonResponse
    {

        $data['password'] = UserService::hashPassword($data['password']);
        $data['is_admin'] = self::NOT_ADMIN;
        try {
            $user = $this->userRepository->createOrUpdate($data);
            Log::info("User created successfully " . json_encode($user));
            return $this->sendResponse($user, "User #$user->id created", 200);
        } catch (\Exception $e) {
            Log::error(" User create fail " . json_encode($data));
            throw $e;
        }
    }

    /**
     * @description update user
     * @param int $id
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    public function update(int $id, array $data): JsonResponse
    {

        if (isset($data['password'])) {
            $data['password'] = UserService::hashPassword($data['password']);
        }

        try {
            $user = $this->userRepository->createOrUpdate($data, $id);
            Log::info("User $id updated successfully " . json_encode($data));
            return $this->sendResponse($user, "User #$user->id updated", 200);
        } catch (\Exception $e) {
            Log::error(" User updated fail " . json_encode($data));
            throw $e;
        }
    }

    /**
     * @description retrive all users
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {

        try {
            $user = $this->userRepository->findById($id);
            Log::info("User retrieved single user data successfully " . json_encode($user));
            return $this->sendResponse($user, "Details of user #$user->id", 200);
        } catch (\Exception $e) {
            Log::error("User $id retrieved single user data fail");
            throw $e;
        }
    }

    /**
     * @description delete
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->userRepository->delete($id);
            Log::info("User $id delete successfully");
            return $this->sendResponse(null, "User #$id deleted", 200);
        } catch (\Exception $e) {
            Log::error("User $id delete fail");
            throw $e;
        }
    }
}
