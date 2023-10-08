<?php

namespace App\Services;

use App\Http\Resources\OrganizationCollection;
use App\Http\Resources\OrganizationResource;
use App\Interfaces\OrganizationRepositoryInterface;
use App\Repositories\OrganizationRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrganizationService
{
    use ApiResponse;

    const PARENT_PERMISSION = 'organizations';

    public OrganizationRepository $orgRepository;

    public function __construct(OrganizationRepository $orgRepository)
    {
        $this->orgRepository = $orgRepository;
    }

    /**
     * @description retrive all organizations
     * @param array $params
     * @return JsonResponse
     * @throws Exception
     */
    public function all(array $params): JsonResponse
    {
        try {
            $orgaList = $this->orgRepository->all($params);
            Log::info("Retrieved all organizations : WEB ");
            $functionName = $orgaList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($orgaList, "List of organizations");
        } catch (Exception $e) {
            Log::error("Retrieved all citizens is error : WEB ");
            throw $e;
        }
    }


    /**
     * @description create an organization
     * @param array $organizationData
     * @return JsonResponse
     * @throws Exception
     */
    public function create(array $organizationData): JsonResponse
    {
        try {
            $organization = $this->orgRepository->createOrUpdate($organizationData);
            Log::info("Citizen created: WEB " . json_encode($organization));
            return $this->sendResponse($organization, "Organization #$organization->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on organization create : WEB " . json_encode($organizationData));
            throw $e;
        }
    }

    /**
     * @description update an organization
     * @param array $organizationData
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(array $organizationData, int $id): JsonResponse
    {
        try {
            $organization = $this->orgRepository->createOrUpdate($organizationData, $id);
            Log::info("Citizen updated: WEB " . json_encode($organization));
            return $this->sendResponse($organization, "Organization #$organization->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on organization update : WEB " . json_encode($organizationData));
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
            $organization = $this->orgRepository->findById($id);
            Log::info("Retrieved single organization by id:$id: WEB ");
            return $this->sendResponse($organization, "Details of organization #$organization->id");
        } catch (Exception $e) {
            Log::error("Error on Retrieve single organization by id:$id: WEB ");
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
            $this->orgRepository->delete($id);
            Log::info("Deleted organization by id:$id: WEB ");
            return $this->sendResponse(null, "Organization #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete organization by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function search(array $searchData): JsonResponse
    {
        try {
            $organizations = $this->orgRepository->globalSearch($searchData);
            Log::info("Search organizations: WEB " . json_encode($organizations));
            $functionName = $organizations instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($organizations, "Get organizations for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on organizations search : WEB " . json_encode($searchData));
            throw $e;
        }
    }
}
