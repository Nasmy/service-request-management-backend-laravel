<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Job;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<Job>
 */
final class JobRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Job>
     */
    protected string $className = Job::class;

    /**
     * @var string[]
     */
    protected array $column_names = ['subject', 'start_date', 'end_date', 'status', 'notes'];

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $jobs = parent::all($params, $this->getFilterQuery($params));
        foreach ($jobs as $job) {
            $this->getCitizenAssignedToUserDocuments($job);
        }
        return $jobs;
    }

    /**
     * @param int $id
     * @return Job
     */
    public function findById(int $id): Job
    {
        $job = parent::findById($id);
        $this->getCitizenAssignedToUserDocuments($job);
        return $job;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Job
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Job
    {
        $job = parent::createOrUpdate($collection, $id);
        $this->getCitizenAssignedToUserDocuments($job);
        return $job;
    }

    /**
     * @param array $collection
     * @param Builder|null $query
     * @return Collection<Job>|LengthAwarePaginator
     */
    public function search(array $collection = [], ?Builder $query = null)
    {
        $result = parent::search($collection, $this->getFilterQuery($collection));
        foreach ($result as $job) {
            $this->getCitizenAssignedToUserDocuments($job);
        }
        return $result;
    }

    /**
     * @param array $searchData
     * @param Builder|null $query
     * @return Collection<Job>|LengthAwarePaginator
     */
    public function globalSearch(array $searchData, ?Builder $query = null)
    {
        $result = parent::globalSearch($searchData, $this->getFilterQuery($searchData));
        foreach ($result as $job) {
            $this->getCitizenAssignedToUserDocuments($job);
        }
        return $result;
    }

    /**
     * @param array $searchData
     * @param string $status
     * @return Collection<Job>|LengthAwarePaginator
     */
    public function statusGlobalSearch(array $searchData, string $status)
    {
        $query = $this->getFilterQuery($searchData);
        $query->where('status', $status);
        if (isset($searchData['search'])) {
            $words = str_word_count($searchData['search'], 1, '0..9À..ÖØ..öø..ÿ');
            $query->where(function (Builder $query) use ($words) {
                foreach ($words as $word) {
                    foreach ($this->column_names as $name) {
                        $query->orWhere($name, 'LIKE', "%$word%");
                    }
                }
            });
        }
        return $this->paginate($searchData, $query);
    }

    /**
     * @param Job $job
     * @return void
     */
    public function getCitizenAssignedToUserDocuments(Job $job)
    {
        if ($job->contact != null) {
            $job->contact->get();
            if ($job->contact->citizen != null)
                $job->contact->citizen->get();
            if ($job->contact->organization != null)
                $job->contact->organization->get();
        }
        if ($job->assignedToUser != null)
            $job->assignedToUser->get();
        $job->documents->all();
    }

    private function getFilterQuery(array $params): Builder {
        $query = Job::query();
        $query->where(function (Builder $query) use ($params) {
            $hasPermissions = Auth::user()->is_admin || Auth::user()->role->permissions->firstWhere('ident', '=', 'jobs.showAll') != null;
            if (!$hasPermissions && (!isset($params['created_by']) || !$params['created_by']) && (!isset($params['assigned_to']) || !$params['assigned_to'])
                || (isset($params['created_by']) && $params['created_by'])) {
                $query->orWhere('created_by', '=', Auth::user()->id);
            }
            if (!$hasPermissions && (!isset($params['created_by']) || !$params['created_by']) && (!isset($params['assigned_to']) || !$params['assigned_to'])
                || (isset($params['assigned_to']) && $params['assigned_to'])) {
                $query->orWhere('assigned_to_user', '=', Auth::user()->id);
            }
        });
        return $query;
    }
}
