<?php

namespace TuoiTre\SSO\Repositories\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use TuoiTre\SSO\Models\Broker;
use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository as BrokerRepositoryInterface;

class BrokerRepository implements BrokerRepositoryInterface
{
    public function __construct(
        protected Model|Eloquent|null $model = null
    ) {
        if (is_null($this->model)) {
            $this->model = new Broker();
        }
    }

    public function findByAttributes(array $attributes)
    {
        return $this->buildQueryByAttributes($attributes)->first();
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes)
    {
        return $this->model->find($id)->update($attributes);
    }

    public function updateOrInsert(array $attributes, array $values)
    {
        return $this->model->updateOrInsert($attributes, $values);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    private function buildQueryByAttributes(
        array $attributes,
        string $orderBy = null,
        string $sortOrder = 'asc'
    ): Builder {
        $query = $this->model->query();

        foreach ($attributes as $field => $value) {
            $query->where($field, $value);
        }

        if ($orderBy !== null) {
            $query->orderBy($orderBy, $sortOrder);
        }
        return $query;
    }
}
