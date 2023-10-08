<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Citizen;
use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<Organization>
 */
final class OrganizationRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Organization>
     */
    protected string $className = Organization::class;

    /**
     * @var string[]
     */
    protected array $column_names = ['name'];

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $organizations = parent::all($params, $query);
        foreach ($organizations as $citizen) {
            $this->getOrganizationContactsCitizen($citizen);
        }
        return $organizations;
    }

    /**
     * @param int $id
     * @return Organization
     */
    public function findById(int $id): Organization
    {
        $organization = parent::findById($id);
        $this->getOrganizationContactsCitizen($organization);
        return $organization;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Organization
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Organization
    {
        $citizen = parent::createOrUpdate($collection, $id);
        $this->getOrganizationContactsCitizen($citizen);
        return $citizen;
    }

    /**
     * @param array $collection
     * @param Builder|null $query
     * @return Collection<Organization>|LengthAwarePaginator
     */
    public function search(array $collection = [], ?Builder $query = null)
    {
        $result = parent::search($collection, $query);
        foreach ($result as $citizen) {
            $this->getOrganizationContactsCitizen($citizen);
        }
        return $result;
    }

    /**
     * @param array $searchData
     * @param Builder|null $query
     * @return Collection<Organization>|LengthAwarePaginator
     */
    public function globalSearch(array $searchData, ?Builder $query = null)
    {
        $result = parent::globalSearch($searchData, $query);
        foreach ($result as $organization) {
            $this->getOrganizationContactsCitizen($organization);
        }
        return $result;
    }

    /**
     * @param Organization $organization
     * @return void
     */
    private function getOrganizationContactsCitizen(Organization $organization) {
        $organization->contacts->all();
        foreach ($organization->contacts as $contact) {
            if ($contact->citizen != null)
                $contact->citizen->get();
        }
    }
}
