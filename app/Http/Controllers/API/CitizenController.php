<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Citizen\CitizenSearchRequest;
use App\Http\Requests\Citizen\CitizenStoreRequest;
use App\Http\Requests\Citizen\CitizenUpdateRequest;
use App\Http\Requests\GlobalSearchRequest;
use App\Http\Requests\PaginatedRequest;
use App\Services\CitizenService;
use Exception;

class CitizenController extends Controller
{
    public CitizenService $citizenService;

    public function __construct(CitizenService $citizenService)
    {
        $this->citizenService = $citizenService;
    }


    /**
     * @throws Exception
     */
    public function index(PaginatedRequest $request)
    {
        return $this->citizenService->all($request->validated());
    }

    /**
     * @throws Exception
     */
    public function store(CitizenStoreRequest $request)
    {
        return $this->citizenService->create($request->validated());
    }

    /**
     * @throws Exception
     */
    public function show(int $id)
    {
        return $this->citizenService->findById($id);
    }

    /**
     * @throws Exception
     */
    public function update(CitizenUpdateRequest $request, int $id)
    {
        return $this->citizenService->update($request->validated(), $id);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id)
    {
        return $this->citizenService->delete($id);
    }

    /**
     * @throws Exception
     */
    public function search(CitizenSearchRequest $request)
    {
        return $this->citizenService->search($request->validated());
    }

    /**
     * @throws Exception
     */
    public function globalSearch(GlobalSearchRequest $request)
    {
        return $this->citizenService->globalSearch($request->validated());
    }
}
