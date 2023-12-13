<?php

namespace Common\app\Indexes;

class AccountIndex extends CoreIndex
{

    public function __construct()
    {
        return parent::__construct('guid');
    }

    public function index(): string
    {
        return 'token_account';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'long',
            ],
            'guid' => [
                'type' => 'keyword'
            ],
            'username' => [
                'type' => 'keyword'
            ],
            'email' => [
                'type' => 'keyword'
            ],
            'phone' => [
                'type' => 'keyword'
            ],
            'id_role' => [
                'type' => 'keyword',
                'fields' => [
                    'array' => [
                        'type' => 'text',
                        'fielddata' => true
                    ]
                ]
            ],
            'name_role' => [
                'type' => 'keyword',
                'fields' => [
                    'array' => [
                        'type' => 'text',
                        'fielddata' => true
                    ]
                ]
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'password_hash' => [
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
