<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Http\Requests\PaginatedRequest;
use App\Services\DocumentService;
use Exception;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{

    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(PaginatedRequest $request)
    {
        return $this->documentService->all($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreDocumentRequest $request)
    {
        return $this->documentService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Document $document)
    {
        return $this->documentService->findById($document->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDocumentRequest $request
     * @param Document $document
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        return $this->documentService->update($document->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Document $document)
    {
        return $this->documentService->delete($document->id);
    }
}
