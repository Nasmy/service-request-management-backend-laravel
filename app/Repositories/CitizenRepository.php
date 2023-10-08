<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Citizen;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


/**
 * @phpstan-extends CrudModelRepository<Citizen>
 */
final class CitizenRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Citizen>
     */
    protected string $className = Citizen::class;

    /**
     * @var string[]
     */
    protected array $column_names = ['lastname', 'firstname', 'birthdate'];

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $citizens = parent::all($params, $query);
        foreach ($citizens as $citizen) {
            $this->getCitizenContactsOrganizations($citizen);
        }
        return $citizens;
    }

    /**
     * @param int $id
     * @return Citizen
     */
    public function findById(int $id): Citizen
    {
        $citizen = parent::findById($id);
        $this->getCitizenContactsOrganizations($citizen);
        return $citizen;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Citizen
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Citizen
    {
        $citizen = parent::createOrUpdate($collection, $id);
        $this->getCitizenContactsOrganizations($citizen);
        return $citizen;
    }

    /**
     * @param array $collection
     * @param Builder|null $query
     * @return Collection<Citizen>|LengthAwarePaginator
     */
    public function search(array $collection = [], ?Builder $query = null)
    {
        $result = parent::search($collection, $query);
        foreach ($result as $citizen) {
            $this->getCitizenContactsOrganizations($citizen);
        }
        return $result;
    }

    /**
     * @param array $collection
     * @return Collection<Citizen>|LengthAwarePaginator
     */
    public function strictSearch(array $collection = [])
    {
        $query = $this->className::query();
        $query->where('lastname', $collection['lastname'])
            ->where('firstname', $collection['firstname']);
        $result = $this->paginate($collection, $query);
        foreach ($result as $citizen) {
            $this->getCitizenContactsOrganizations($citizen);
        }
        return $result;
    }

    /**
     * @param array $searchData
     * @param Builder|null $query
     * @return Collection<Citizen>|LengthAwarePaginator
     */
    public function globalSearch(array $searchData, ?Builder $query = null)
    {
        $result = parent::globalSearch($searchData, $query);
        foreach ($result as $citizen) {
            $this->getCitizenContactsOrganizations($citizen);
        }
        return $result;
    }

    /**
     * @param Citizen $citizen
     * @return void
     */
    private function getCitizenContactsOrganizations(Citizen $citizen)
    {
        $citizen->contacts->all();
        foreach ($citizen->contacts as $contact) {
            if ($contact->organization != null)
                $contact->organization->get();
        }
    }
}
