<?php

namespace App\Modules\Token\Services\Interfaces;

interface EndpointServiceInterface
{
    /**
     * @param array $search
     * @return array
     */
    public function getOneEndpoint(array $search = []): array;

    /**
     * @param int $currentPage
     * @param int $perPage
     * @param array $search
     * @return array
     */
    public function getListEndpoint(int $currentPage, int $perPage, array $search = [], array $conditions = []): array;

    /**
     * @param array $data
     * @param $id
     * @return array
     */
    public function update(array $data, $id): array;

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data): array;
}
