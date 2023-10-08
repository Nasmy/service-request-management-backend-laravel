<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<Contact>
 */
final class ContactRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Contact>
     */
    protected string $className = Contact::class;

    /**
     * @var string[]
     */
    protected array $column_names = ['email', 'mobile', 'phone', 'address', 'city', 'zip'];

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $contacts = parent::all($params, $query);
        foreach ($contacts as $contact) {
            $this->getContactCitizenOrganizationJobs($contact);
        }
        return $contacts;
    }

    /**
     * @param int $id
     * @return Contact
     */
    public function findById(int $id): Contact
    {
        $contact = parent::findById($id);
        $this->getContactCitizenOrganizationJobs($contact);
        return $contact;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Contact
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Contact
    {
        $contact = parent::createOrUpdate($collection, $id);
        $this->getContactCitizenOrganizationJobs($contact);
        return $contact;
    }

    /**
     * @param array $collection
     * @param Builder|null $query
     * @return Collection<Contact>|LengthAwarePaginator
     */
    public function search(array $collection = [], ?Builder $query = null)
    {
        $result = parent::search($collection, $query);
        foreach ($result as $contact) {
            $this->getContactCitizenOrganizationJobs($contact);
        }
        return $result;
    }

    /**
     * @param array $searchData
     * @param Builder|null $query
     * @return Collection<Contact>|LengthAwarePaginator
     */
    public function globalSearch(array $searchData, ?Builder $query = null)
    {
        $result = parent::globalSearch($searchData, $query);
        foreach ($result as $contact) {
            $this->getContactCitizenOrganizationJobs($contact);
        }
        return $result;
    }

    /**
     * @param Contact $contact
     * @return void
     */
    private function getContactCitizenOrganizationJobs(Contact $contact)
    {
        if ($contact->citizen != null)
            $contact->citizen->get();
        if ($contact->organization != null)
            $contact->organization->get();
        $contact->jobs->all();
        foreach ($contact->jobs as $job) {
            if ($job->assignedToUser != null)
                $job->assignedToUser->get();
            $job->documents->all();
        }
    }
}
