<?php

namespace App\Services;

use App\Http\Resources\ContactResource;
use App\Repositories\ContactRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ContactService
{
    use ApiResponse;

    public ContactRepository $contactRepository;

    const PARENT_PERMISSION = 'contacts';

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @description retrive all contacts
     * @param $params
     * @return JsonResponse
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $contactList = $this->contactRepository->all($params);
            Log::info("Retrieved all contacts : WEB ");
            $functionName = $contactList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($contactList, "List of contacts");
        } catch (Exception $e) {
            Log::error("Retrieved all contacts is error : WEB ");
            throw $e;
        }
    }


    /**
     * @description create contact
     * @param array $contactData
     * @return JsonResponse
     * @throws Exception
     */
    public function create(array $contactData): JsonResponse
    {
        try {
            $contact = $this->contactRepository->createOrUpdate($contactData);
            Log::info("Contact created: WEB " . json_encode($contact));
            return $this->sendResponse($contact, "Contact #$contact->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on contact create : WEB " . json_encode($contactData));
            throw $e;
        }
    }

    /**
     * @description update contact
     * @param array $contactData
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(array $contactData, int $id): JsonResponse
    {
        try {
            $contact = $this->contactRepository->createOrUpdate($contactData, $id);
            Log::info("Contact updated: WEB " . json_encode($contact));
            return $this->sendResponse($contact, "Contact #$contact->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on contact update : WEB " . json_encode($contactData));
            throw $e;
        }
    }

    /**
     * @description retreive all contacts
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {
        try {
            $contact = $this->contactRepository->findById($id);
            Log::info("Retrieved single contact by id:$id: WEB ");
            return $this->sendResponse($contact, "Details of contact #$contact->id");
        } catch (Exception $e) {
            Log::error("Error on Retrieve single contact by id:$id: WEB ");
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
            $this->contactRepository->delete($id);
            Log::info("deleted contact by id:$id: WEB ");
            return $this->sendResponse(null, "Contact #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete contact by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function search(array $searchData): JsonResponse
    {
        try {
            $contacts = $this->contactRepository->search($searchData);
            Log::info("Search contacts: WEB " . json_encode($contacts));
            $functionName = $contacts instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($contacts, "Get contacts for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on contacts search : WEB " . json_encode($searchData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function globalSearch(array $searchData): JsonResponse
    {
        try {
            $contacts = $this->contactRepository->globalSearch($searchData);
            Log::info("Search contacts: WEB " . json_encode($contacts));
            $functionName = $contacts instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($contacts, "Get contacts for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on contacts search : WEB " . json_encode($searchData));
            throw $e;
        }
    }
}
