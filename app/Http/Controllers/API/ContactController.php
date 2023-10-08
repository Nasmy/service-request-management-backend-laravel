<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Citizen\CitizenSearchRequest;
use App\Http\Requests\Contact\GlobalSearchContactRequest;
use App\Http\Requests\Contact\SearchContactRequest;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Requests\GlobalSearchRequest;
use App\Http\Requests\PaginatedRequest;
use App\Services\ContactService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(PaginatedRequest $request)
    {
        return $this->contactService->all($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContactRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreContactRequest $request)
    {
        return $this->contactService->create($request->validated());
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
        return $this->contactService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContactRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateContactRequest $request, int $id)
    {
        return $this->contactService->update($request->validated(), $id);
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
        return $this->contactService->delete($id);
    }

    /**
     * @throws Exception
     */
    public function search(SearchContactRequest $request)
    {
        return $this->contactService->search($request->validated());
    }

    /**
     * @throws Exception
     */
    public function globalSearch(GlobalSearchRequest $request)
    {
        return $this->contactService->globalSearch($request->validated());
    }
}
