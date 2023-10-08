<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Job;
use App\Repositories\DocumentRepository;
use App\Traits\ApiResponse;
use Auth;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    use ApiResponse;

    const PARENT_PERMISSION = 'documents';

    public DocumentRepository $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $documentList = $this->documentRepository->all($params);
            Log::info("Retrieved all documents : WEB ");
            $functionName = $documentList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($documentList, "List of documents");
        } catch (Exception $e) {
            Log::error("Retrieved all citizens is error : WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $documentData): JsonResponse
    {
        // Uploading file to cloud storage
        $files = $documentData['documents'];
        try {
            if (is_array($files)) {
                $response = new Collection();

                /** @var UploadedFile $file */
                foreach ($files as $file) {
                    $document = $this->createOrUpdateDocument($file, $documentData, $documentData['job_id']);
                    $response->push($document);
                    Log::info("Citizen created: WEB " . json_encode($document));
                }
                return $this->sendResponse($response, "Documents {$response->map(function (Document $document): int {return $document->id;})} created", 201);
            }

            $document = $this->createOrUpdateDocument($files, $documentData, $documentData['job_id']);
            return $this->sendResponse($document, "Document #$document->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on document create : WEB " . json_encode($files));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $documentData): JsonResponse
    {
        $file = $documentData['document'];

        try {
            $document = $this->documentRepository->findById($id);

            $oldPath = $document->path;

            if (isset($oldPath) && Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }

            $document = $this->createOrUpdateDocument($file, $documentData, $document->job_id, $id);
            Log::info("Document updated: WEB " . json_encode($document));
            return $this->sendResponse($document, "Document #$document->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on document update : WEB " . json_encode($file));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {
        try {
            $document = $this->documentRepository->findById($id);
            Log::info("Retrieved single document by id:$id: WEB ");
            return $this->sendResponse($document, "Details of document #$document->id");
        } catch (Exception $e) {
            Log::error("Error on Retrieve single document by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $document = $this->documentRepository->findById($id);
            $oldPath = $document->path;
            if (isset($oldPath) && Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
            $this->documentRepository->delete($id);
            Log::info("deleted document by id:$id: WEB ");
            return $this->sendResponse(null, "Document #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete document by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @param UploadedFile $file
     * @param array $documentData
     * @param int $jobId
     * @param int|null $id
     * @return Document
     */
    private function createOrUpdateDocument(UploadedFile $file, array $documentData, int $jobId, ?int $id = null): Document
    {
        $name = substr_replace($file->getClientOriginalName(), '_' . (new DateTime())->format('Y-m-d - H:i:s:v'), -(strlen($file->getClientOriginalExtension()) + 1), 0);
        $documentData['path'] = $file->storeAs("documents/$jobId", $name);
        $documentData['type'] = $file->getMimeType();
        $documentData['name'] = $name;
        return $this->documentRepository->createOrUpdate($documentData, $id);
    }
}
