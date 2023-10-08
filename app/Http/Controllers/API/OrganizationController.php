<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\GlobalSearchRequest;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Http\Requests\PaginatedRequest;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationController extends ApiController
{

    public OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(PaginatedRequest $request)
    {
        return $this->organizationService->all($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrganizationRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreOrganizationRequest $request)
    {
        return $this->organizationService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(int $id)
    {
        return $this->organizationService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrganizationRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateOrganizationRequest $request, int $id)
    {
        return $this->organizationService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id)
    {
        return $this->organizationService->delete($id);
    }

    /**
     * @throws Exception
     */
    public function search(GlobalSearchRequest $request)
    {
        return $this->organizationService->search($request->validated());
    }
}
