<?php

namespace App\Modules\Token\Services\Interfaces;

interface TokenServiceInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function generate(array $data): array;

    /**
     * @param array $data
     * @return array
     */
    public function verify(array $data): array;
}
