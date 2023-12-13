<?php

namespace TuoiTre\SSO\Repositories\Interfaces;

interface Repository
{
    public function create(array $attributes);

    public function update(int $id, array $attributes);

    public function updateOrInsert(array $attributes, array $values);

    public function findById(int $id);

    public function findByAttributes(array $attributes);
}
