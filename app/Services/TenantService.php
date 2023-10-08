<?php

namespace App\Services;

use App\Interfaces\TenantRepositoryInterface;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;

class TenantService
{
    use ApiResponse;

    public UserRepository $userRepository;
    public TenantRepositoryInterface $tenantRepository;

    public function __construct(TenantRepositoryInterface $tenantRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->tenantRepository = $tenantRepository;
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function createTenant($data): array
    {
        $response = [];
        $data['password'] = UserService::hashPassword($data['password']);
        $tenantId = $data['username'];
        $domainName = TenantService::generateDomainName($tenantId);
        try {
            // Get connection for pdo. In php v8 DB::beginTransaction() not support
            $pdo = DB::connection()->getPdo();
            $pdo->beginTransaction();
            $user = $this->userRepository->createOrUpdate($data, null);
            $user['is_admin'] = Role::DEFAULT_ADMIN_ROLE_ID;
            $tenant = $this->tenantRepository->createTenant($tenantId, $user);
            $this->tenantRepository->createDomain($tenant, $domainName);
            $response['token'] = AuthService::generateAuthToken($user, AuthService::TOKEN_API);
            $response['first_name'] = $user->last_name;
            DB::commit();
            $response['is_created'] = true;
            Log::info("Tenant Created : WEB " . json_encode($response));
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            $response['error'] = $e->getMessage();
            Log::error("Tenant Created Fail : WEB message=>" . json_encode($response['error']));
            throw $e;
        }
    }


    public function updateAdminPassword($userId, $formData)
    {
        $password = UserService::hashPassword($formData['password']);
        try {
            DB::connection()->getPdo()->beginTransaction();
            $user = $this->userRepository->update($userId, ['password' => $password]);
            $tenant =  $this->tenantRepository->updateTenant($user->username, $user);
            DB::connection()->getPdo()->commit();
            Log::info("Tenant updated : WEB", $tenant->toArray());
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            Log::error("Tenant update failure : WEB " . json_encode($e->getMessage()));
            throw $e;
        }
    }

    /**
     * @param $userName
     * @return string
     */
    public static function generateDomainName($userName): string
    {
        $domainName =  $userName . '.' . config('app.tenant_domain');
        Log::info("Domain Name generated  : WEB " . json_encode($domainName));
        return $domainName;
    }
}
