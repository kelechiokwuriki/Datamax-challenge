<?php

namespace App\Repositories\Base;
use App\Repositories\Base\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements Repository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function returnPaginated(int $paginateAmount)
    {
        return $this->model->paginate($paginateAmount);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $result = $this->model->find($id);

        if ($result) {
            return $result->update($data);
        }

        throw new ModelNotFoundException('Resource with id '. $id. ' does not exist');
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    public function where($column, $value)
    {
        return $this->model->where($column, $value);
    }

}
