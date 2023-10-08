<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalSearchRequest;
use App\Http\Requests\Job\JobGlobalSearchRequest;
use App\Http\Requests\Job\JobIndexRequest;
use App\Http\Requests\Job\JobSearchRequest;
use App\Http\Requests\Job\JobStoreRequest;
use App\Http\Requests\Job\JobUpdateRequest;
use App\Services\JobService;
use Exception;

class JobController extends Controller
{
    public JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * @throws Exception
     */
    public function index(JobIndexRequest $request)
    {
        return $this->jobService->all($request->validated());
    }

    /**
     * @throws Exception
     */
    public function store(JobStoreRequest $request)
    {
        return $this->jobService->create($request->validated());
    }

    /**
     * @throws Exception
     */
    public function show(int $id)
    {
        return $this->jobService->findById($id);
    }

    /**
     * @throws Exception
     */
    public function update(JobUpdateRequest $request, int $id)
    {
        return $this->jobService->update($request->validated(), $id);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id)
    {
        return $this->jobService->delete($id);
    }

    /**
     * @throws Exception
     */
    public function search(JobSearchRequest $request)
    {
        return $this->jobService->search($request->validated());
    }

    /**
     * @throws Exception
     */
    public function globalSearch(JobGlobalSearchRequest $request)
    {
        return $this->jobService->globalSearch($request->validated());
    }

    /**
     * @throws Exception
     */
    public function statusGlobalSearch(JobGlobalSearchRequest $request, string $status) {
        return $this->jobService->statusGlobalSearch($request->validated(), $status);
    }
}
