<?php

namespace Common\App\Indexes;

class RoleIndex extends CoreIndex
{

    public function __construct()
    {
        return parent::__construct('db_id');
    }

    public function index(): string
    {
        return 'token_account_role';
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
            'id_permission' => [
                'type' => 'keyword',
                'fields' => [
                    'array' => [
                        'type' => 'text',
                        'fielddata' => true
                    ]
                ]
            ],

            'name_permission' => [
                'type' => 'keyword',
                'fields' => [
                    'array' => [
                        'type' => 'text',
                        'fielddata' => true
                    ]
                ]
            ],
            'status' => [
                'type' => 'keyword',
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
