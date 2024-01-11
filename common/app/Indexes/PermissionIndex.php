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
        return 'token_permissions';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'long',
            ],
            'name' => [
                'type' => 'keyword'
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'db_id' => [
                'type' => 'keyword',
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
