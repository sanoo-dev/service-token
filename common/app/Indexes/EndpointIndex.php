<?php

namespace Common\App\Indexes;


class EndpointIndex extends CoreIndex
{
    public function __construct()
    {
        return parent::__construct('db_id');
    }

    public function index(): string
    {
        return 'token_endpoints';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'keyword',
            ],
            'name' => [
                'type' => 'keyword'
            ],
            'server_ip' => [
                'type' => 'keyword'
            ],
            'domain' => [
                'type' => 'keyword'
            ],
            'db_id' => [
                'type' => 'keyword'
            ],
            'public_key' => [
                'type' => 'keyword'
            ],
            'private_key' => [
                'type' => 'keyword'
            ],
            'public_key_new' => [
                'type' => 'keyword'
            ],
            'private_key_new' => [
                'type' => 'keyword'
            ],
            'tracker' => [
                'type' => 'keyword'
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'expire' => [
                'type' => 'keyword'
            ],
            'service_id' => [
                'type' => 'keyword'
            ],
            'created_at' => [
                'type' => 'keyword'
            ],
            'updated_at' => [
                'type' => 'keyword'
            ],
        ];
    }
}
