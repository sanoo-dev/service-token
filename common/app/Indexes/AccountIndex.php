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
        return 'token_accounts';
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
            'email' => [
                'type' => 'keyword'
            ],
            'password_hash' => [
                'type' => 'keyword'
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'roles' => [
                'properties' => [
                    'id' => ['type' => 'long'],
                    'name' => ['type' => 'keyword'],
                ]
            ],
            'permissions' => [
                'properties' => [
                    'id' => ['type' => 'long'],
                    'name' => ['type' => 'keyword'],
                    'permissions' => [
                        'properties' => [
                            'id' => ['type' => 'long'],
                            'name' => ['type' => 'keyword'],
                        ],
                    ],
                ],
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
