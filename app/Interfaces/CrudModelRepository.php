<?php

namespace App\Interfaces;

use App\Models\CrudModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * @phpstan-template T of \App\Models\CrudModel
 */
abstract class CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<T>
     */
    protected string $className;

    /**
     * @var string[]
     */
    protected array $column_names;

    /**
     * @param array $params
     * @param Builder|null $query
     * @return Collection|LengthAwarePaginator
     */
    public function all(array $params, ?Builder $query = null)
    {
        $query = $query ?? $this->className::query();
        if (isset($params['column']) && isset($params['operator']) && isset($params['value']))
            $query->where($params['column'], $params['operator'], $params['value']);
        return $this->paginate($params, $query);
    }

    /**
     * @param int $id
     * @return T
     */
    public function findById(int $id)
    {
        $model = $this->className::findOrFail($id);
        $this->getCreatedByUpdatedBy($model);
        return $model;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return T
     */
    public function createOrUpdate(array $collection = [], ?int $id = null)
    {
        if ($id != null) {
            $this->className::findOrFail($id);
            $userArray = ['updated_by' => Auth::user()->id];
        } else {
            $userArray = ['created_by' => Auth::user()->id];
        }
        $model = $this->className::updateOrCreate(['id' => $id], array_merge($collection, $userArray));
        $this->getCreatedByUpdatedBy($model);
        return $model;
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): ?bool
    {
        return $this->className::findOrFail($id)->delete();
    }

    /**
     * @param array $collection
     * @param Builder|null $query
     * @return Collection<T>|LengthAwarePaginator
     */
    protected function search(array $collection = [], ?Builder $query = null)
    {
        $query = $query ?? $this->className::query();
        $query->where(function (Builder $query) use ($collection){
            foreach ($collection as $key => $value) {
                if ($value != null && !in_array($key, ["assigned_to", "created_by", "all", "page", "per_page"]))
                    $query->orWhere($key, 'LIKE', "%$value%");
            }
        });
        return $this->paginate($collection, $query);
    }

    /**
     * @param array $searchData
     * @param Builder|null $query
     * @return Collection<T>|LengthAwarePaginator
     */
    protected function globalSearch(array $searchData, ?Builder $query = null)
    {
        $query = $query ?? $this->className::query();
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
     * @param array $params
     * @param Builder $query
     * @return Collection<T>|LengthAwarePaginator
     */
    protected function paginate(array $params, Builder $query)
    {
        if (isset($params['all']) && $params['all']) {
            $models = $query->get();
        } else {
            $perPage = (int)config('app.pagination.per_page');
            if (isset($params['per_page'])) {
                $perPage = (int)$params['per_page'];
            }

            $currentPage = config('app.pagination.default_page');
            if (isset($params['page'])) {
                $currentPage = (int)$params['page'];
            }
            $models = $query->paginate($perPage, ['*'], 'page', $currentPage)->withQueryString();
        }
        foreach ($models as $model) {
            $this->getCreatedByUpdatedBy($model);
        }
        return $models;
    }

    private function getCreatedByUpdatedBy(CrudModel $model)
    {
        if ($model->createdBy != null)
            $model->createdBy->get();
        if ($model->updatedBy != null)
            $model->updatedBy->get();
    }
}
