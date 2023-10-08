<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<User>
 */
final class UserRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<User>
     */
    protected string $className = User::class;

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $users = parent::all($params, $query);
        foreach ($users as $user) {
            $this->getUserRole($user);
        }
        return $users;
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        $citizen = parent::findById($id);
        $this->getUserRole($citizen);
        return $citizen;
    }

    public function create($user)
    {
        $userObject = [
            'first_name' => $user['first_name'],
            'last_name' => $user['first_name'],
            'organization' => $user['first_name'],
            'mobile' => $user['mobile'],
            'email' => $user['email'],
            'username' => $user['username'],
            'city' => $user['city'],
            'zip' => $user['zip'],
            'role_id' => $user['role_id'],
            'password' => $user['password'],
            'address' => $user['address'],
        ];


        return User::create($userObject);
    }

    public function update($id, $request)
    {
        $user = User::findOrFail($id);
        $user->fill($request);
        $user->save();
        return $user;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return User
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): User
    {
        $user = parent::createOrUpdate($collection, $id);
        $this->getUserRole($user);
        return $user;
    }


    public function deleteTenantUser($id): string
    {

        $user = User::find($id);
        $username = $user->username;
        Tenant::destroy($username);

        if ($user->delete()) {
            return 'true';
        }
        return 'false';
    }

    /**
     * @description search tenant user
     * @param array $collection
     * @param Builder|null $query
     * @return object
     */
    public function search(array $collection = [], ?Builder $query = null): object
    {

        //checking url query have page params
        $currentPage = config('app.pagination.default_page');
        if (isset($collection['page'])) {
            $currentPage = $collection['page'];
        }

        $users = DB::table('users')
            ->join('domains', 'domains.user_id', '=', 'users.id');
        foreach ($collection as $key => $value) {
            if ($value != '' && $key != '_token' && $key != 'page') {
                switch ($key) {
                    case 'role_id':
                        $users->where('role_id', $value);
                        break;
                    case 'domain':
                        $users->where('domains.domain', 'like', $value . '%');
                        break;
                    default:
                        $users->where($key, 'like', $value . '%');
                }
            }
        }

        return $users->paginate(config('app.pagination.per_page'), ['*'], 'page', $currentPage)->withQueryString();
    }

    private function getUserRole(User $user)
    {
        if ($user->role != null)
            $user->role->get();
    }
}
