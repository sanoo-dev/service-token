<?php

namespace Common\App\Indexes;

class ServiceIndex extends CoreIndex
{
    public function __construct()
    {
        return parent::__construct('db_id');
    }

    public function index(): string
    {
        return 'token_services';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'long',
            ],
            'app_id' => [
                'type' => 'keyword'
            ],
            'name' => [
                'type' => 'keyword'
            ],
            'status' => [
                'type' => 'integer'
            ],
            'server_ip' => [
                'type' => 'keyword'
            ],
            'domain' => [
                'type' => 'keyword'
            ],
            'endpoint_ip' => [
                'type' => 'keyword'
            ],
            'endpoint_domain' => [
                'type' => 'keyword'
            ],
            'token_type' => [
                'type' => 'keyword'
            ],
            'partner_code' => [
                'type' => 'keyword'
            ],
            'meta' => [
                'type' => 'keyword'
            ],
            'content' => [
                'type' => 'keyword'
            ],
            'db_id' => [
                'type' => 'integer'
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
