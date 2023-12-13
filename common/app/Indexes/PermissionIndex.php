<?php

namespace Common\App\Indexes;

class PermissionIndex extends CoreIndex
{

    public function __construct()
    {
        return parent::__construct('db_id');
    }

    public function index(): string
    {
        return 'token_account_permission';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'long',
            ],
            'action' => [
                'type' => 'keyword'
            ],
            'route' => [
                'type' => 'keyword'
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'created_at' => [
                'type' => 'keyword'
            ],
            'updated_at' => [
                'type' => 'keyword'
            ],
            'db_id' => [
                'type' => 'keyword',
            ],
        ];
    }
}
