<?php

namespace App\Modules\Token\Services\Interfaces;

interface ServiceServiceInterface
{
    /**
     * @param array $search
     * @return array
     */
    public function getOneService(array $search = []): array;

    /**
     * @param int $currentPage
     * @param int $perPage
     * @param array $search
     * @param array $conditions
     * @return array
     */
    public function getListService(int $currentPage = 1, int $perPage = 5, array $search = [], array $conditions = []): array;

    /**
     * @param array $data
     * @param $id
     * @return array
     */
    public function update(array $data, $id);

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data): array;
}
