<?php

namespace App\Services;

use App\Repositories\JobRepository;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class JobService
{
    use ApiResponse;
    const PARENT_PERMISSION = 'jobs';

    public JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    /**
     * @throws Exception
     */
    public function all($params): JsonResponse
    {
        try {
            $jobList = $this->jobRepository->all($params);
            Log::info("Retrieved all jobs : WEB ");
            $functionName = $jobList instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($jobList, "List of jobs");
        } catch (Exception $e) {
            Log::error("Retrieved all jobs is error : WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): JsonResponse
    {
        try {
            $job = $this->jobRepository->findById($id);
            Log::info("Retrieved single job by id:$id: WEB ");
            return $this->sendResponse($job, "Details of job #$job->id", 200);
        } catch (Exception $e) {
            Log::error("Error on Retrieve single job by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $jobData): JsonResponse
    {
        try {
            $job = $this->jobRepository->createOrUpdate($jobData);
            Log::info("Job created: WEB ".json_encode($job));
            return $this->sendResponse($job, "Job #$job->id created", 201);
        } catch (Exception $e) {
            Log::error("Error on job create : WEB ".json_encode($jobData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(array $jobData, int $id): JsonResponse
    {
        try {
            $job = $this->jobRepository->createOrUpdate($jobData, $id);
            Log::info("Job updated: WEB ".json_encode($job));
            return $this->sendResponse($job, "Job #$job->id updated", 201);
        } catch (Exception $e) {
            Log::error("Error on job update : WEB ".json_encode($jobData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->jobRepository->delete($id);
            Log::info("deleted job by id:$id: WEB ");
            return $this->sendResponse(null, "Citizen #$id deleted", 200);
        } catch (Exception $e) {
            Log::info("Error on delete jobk by id:$id: WEB ");
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function search(array $searchData): JsonResponse
    {
        try {
            $jobs = $this->jobRepository->search($searchData);
            Log::info("Search jobs: WEB " . json_encode($jobs));
            $functionName = $jobs instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($jobs, "Get jobs for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on job search : WEB " . json_encode($searchData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function globalSearch(array $searchData): JsonResponse
    {
        try {
            $jobs = $this->jobRepository->globalSearch($searchData);
            Log::info("Search jobs: WEB " . json_encode($jobs));
            $functionName = $jobs instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($jobs, "Get jobs for query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on job search : WEB " . json_encode($searchData));
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function statusGlobalSearch(array $searchData, string $status): JsonResponse {
        try {
            $jobs = $this->jobRepository->statusGlobalSearch($searchData, $status);
            Log::info("Search jobs: WEB " . json_encode($jobs));
            $functionName = $jobs instanceof LengthAwarePaginator ? 'sendResponsePaginated' : 'sendResponse';
            return $this->$functionName($jobs, "Get jobs for status $status and query " . json_encode($searchData), 200);
        } catch (Exception $e) {
            Log::error("Error on job search : WEB " . json_encode($searchData));
            throw $e;
        }
    }
}
