<?php

namespace Fatihirday\RepositoryMake\Services\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentRepository
{
    /**
     * @param Model $model
     */
    public function __construct(private readonly Model $model)
    {
        //
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->newQuery()->get();
    }

    /**
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function multipleCreate(array $data): bool
    {
        return $this->model->newQuery()->insert($data);
    }


    /**
     * @param int $id
     * @return Model|null
     */
    public function getById(int $id): ?Model
    {
        return $this->model->newQuery()->find($id);
    }

    /**
     * @param array $attributes
     * @param array $data
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $data): Model
    {
        return $this->model->newQuery()->updateOrCreate($attributes, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->newQuery()
            ->where(['id' => $id])
            ->update($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function delete(array $data): bool
    {
        return $this->model->newQuery()->where($data)->forceDelete();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->model->newQuery()->find($id)->forceDelete();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->model->newQuery()->filter()->latest('id')->paginate(request('per_page', 50));
    }

    /**
     * @return Collection
     */
    public function active(): Collection
    {
        return $this->model->newQuery()->active()->get();
    }

    /**
     * @param array|null $where
     * @param array|null $with
     * @param array|null $orders
     * @param array|null $orWhere
     * @param array|null $whereIn
     * @param array|null $select
     * @param array|null $whereHas
     * @param array|null $whereHasIn
     * @return Collection
     */
    public function get(
        ?array $where = null,
        ?array $with = null,
        ?array $orders = null,
        ?array $orWhere = null,
        ?array $whereIn = null,
        ?array $select = null,
        ?array $whereHas = null,
        ?array $whereHasIn = null
    ): Collection {
        return $this->model->newQuery()
            ->when($select, function ($query) use ($select) {
                $query->select($select);
            })
            ->when($where, function ($query) use ($where) {
                $query->where($where);
            })
            ->when($whereHas, function ($query) use ($whereHas) {
                foreach ($whereHas as $relation => $relationWhere) {
                    $query->whereHas($relation, function ($q) use ($relationWhere) {
                        return $q->where($relationWhere);
                    });
                }
            })
            ->when($whereHasIn, function ($query) use ($whereHasIn) {
                foreach ($whereHasIn as $relation => $relationWhere) {
                    $query->whereHas($relation, function ($q) use ($relationWhere) {
                        return $q->whereIn(...$relationWhere);
                    });
                }
            })
            ->when($orWhere, function ($query) use ($orWhere) {
                foreach ($orWhere as $key => $where) {
                    $query->orWhere($key, $where);
                }
            })
            ->when($whereIn, function ($query) use ($whereIn) {
                $query->whereIn(...$whereIn);
            })
            ->when($with, function ($query) use ($with) {
                $query->with($with);
            })
            ->when($orders, function ($query) use ($orders) {
                foreach ($orders as $column => $order) {
                    $query->orderBy($column, $order);
                }
            })
            ->get();
    }

    /**
     * @param array $where
     * @param array|null $with
     * @param array|null $whereHas
     * @param array|null $select
     * @return Model|null
     */
    public function getFirst(
        array $where,
        array $with = null,
        array $whereHas = null,
        array $select = null
    ): ?Model
    {
        return $this->model->newQuery()
            ->when($select, function ($query) use ($select) {
                $query->select($select);
            })
            ->when($with, function ($query) use ($with) {
                $query->with($with);
            })
            ->when($whereHas, function ($query) use ($whereHas) {
                foreach ($whereHas as $relation => $relationWhere) {
                    $query->whereHas($relation, function ($q) use ($relationWhere){
                        return $q->where($relationWhere);
                    });
                }
            })
            ->where($where)
            ->first();
    }
}
