<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAttributesRegistrationRelated($attributes)
    {
        $this->model->with($attributes);
    }

    public function filter($filters)
    {
        $filters = explode(';', $filters);

        foreach ($filters as $key => $filter) {
            $where = explode(':', $filter);
            $this->model = $this->model->where($where[0], $where[1], $where[2]);
        }
    }

    public function selectAttributes($attributes)
    {
        $this->model = $this->model
            ->selectRaw($attributes);
    }

    public function getResult()
    {
        return $this->model->get();
    }

    public function getResultPaginate($registros)
    {
        return $this->model->paginate($registros);
    }
}
