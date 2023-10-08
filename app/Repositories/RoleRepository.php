<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<Role>
 */
final class RoleRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Role>
     */
    protected string $className = Role::class;

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $roles = parent::all($params, $query);
        foreach ($roles as $role) {
            $this->getRolePermissionsUsers($role);
        }
        return $roles;
    }

    /**
     * @param int $id
     * @return Role
     */
    public function findById(int $id): Role
    {
        $role = parent::findById($id);
        $this->getRolePermissionsUsers($role);
        return $role;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Role
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Role
    {
        $role = parent::createOrUpdate($collection, $id);
        if (isset($collection['permissions']))
            $role->permissions()->sync($collection['permissions']);
        $this->getRolePermissionsUsers($role);
        return $role;
    }

    /**
     * @param Role $role
     * @return void
     */
    private function getRolePermissionsUsers(Role $role)
    {
        $role->permissions->all();
        $role->users->all();
    }
}
