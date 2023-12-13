<?php

namespace Common\App\Indexes;


class EndPointIndex extends CoreIndex
{

    public function __construct()
    {
        return parent::__construct('db_id');
    }

    public function index(): string
    {
        return 'token_end_points';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'keyword',
            ],
            'serveIp' => [
                'type' => 'keyword'
            ],
            'name' => [
                'type' => 'keyword'
            ],
            'domain' => [
                'type' => 'keyword'
            ],
            'db_id' => [
                'type' => 'keyword'
            ],
            'publicKey' => [
                'type' => 'keyword'
            ],
            'privateKey' => [
                'type' => 'keyword'
            ],
            'newPublicKey' => [
                'type' => 'keyword'
            ],
            'newPrivateKey' => [
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
            'idServices' => [
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
