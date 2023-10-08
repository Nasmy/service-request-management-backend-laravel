<?php

namespace App\Services;

use App\Repositories\CitizenRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CitizenService
{
    use ApiResponse;

    const PARENT_PERMISSION = 'citizens';

    public CitizenRepository $citizenRepository;

    public function __construct(CitizenRepository $citizenRepository)
    {
        $this->citizenRepository = $citizenRepository;
    }

    /**
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $citizenList = $this->citizenRepository->all($params);
            Log::info("Retrieved all citizens : WEB ");
            $functionName = $citizenList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($citizenList, "List of citizens");
        } catch (Exception $e) {
            Log::error("Retrieved all citizens is error : WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {
        try {
            $citizen = $this->citizenRepository->findById($id);
            Log::info("Retrieved single citizen by id:$id: WEB ");
            return $this->sendResponse($citizen, "Details of citizen #$citizen->id");
        } catch (Exception $e) {
            Log::error("Error on Retrieve single citizen by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $citizenData): JsonResponse
    {
        try {
            $citizen = $this->citizenRepository->createOrUpdate($citizenData);
            Log::info("Citizen created: WEB " . json_encode($citizen));
            return $this->sendResponse($citizen, "Citizen #$citizen->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on citizen create : WEB " . json_encode($citizenData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(array $citizenData, int $id): JsonResponse
    {
        try {
            $citizen = $this->citizenRepository->createOrUpdate($citizenData, $id);
            Log::info("Citizen updated: WEB " . json_encode($citizen));
            return $this->sendResponse($citizen, "Citizen #$citizen->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on citizen update : WEB " . json_encode($citizenData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->citizenRepository->delete($id);
            Log::info("deleted citizen by id:$id: WEB ");
            return $this->sendResponse(null, "Citizen #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete citizen by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function search(array $searchData): JsonResponse
    {
        try {
            $citizens = $this->citizenRepository->search($searchData);
            Log::info("Search citizens: WEB " . json_encode($citizens));
            $functionName = $citizens instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($citizens, "Get citizens for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on citizen search : WEB " . json_encode($searchData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function globalSearch(array $searchData): JsonResponse
    {
        try {
            $citizens = $this->citizenRepository->globalSearch($searchData);
            Log::info("Search citizens: WEB " . json_encode($citizens));
            $functionName = $citizens instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($citizens, "Get citizens for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on citizen search : WEB " . json_encode($searchData));
            throw $e;
        }
    }
}
