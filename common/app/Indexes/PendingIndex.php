<?php

namespace Common\app\Indexes;


class PendingIndex extends CoreIndex
{

    public function __construct()
    {
        return parent::__construct('id');
    }

    public function index(): string
    {
        return 'token_pending';
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
            'domain' => [
                'type' => 'keyword'
            ],
            'flag' => [
                'type' => 'keyword'
            ],
            'publicKey' => [
                'type' => 'keyword'
            ],
            'privateKey' => [
                'type' => 'keyword'
            ],
            'tracker' => [
                'type' => 'keyword'
            ],
            'expire' => [
                'type' => 'keyword'
            ],
            'idServices' => [
                'type' => 'keyword'
            ],
            'partnerCode' => [
                'type' => 'keyword'
            ],
            'secretKey' => [
                'type' => 'keyword'
            ],
            'appId' => [
                'type' => 'keyword'
            ],
            'appName' => [
                'type' => 'keyword'
            ],

            'serveIpTransfer' => [
                'type' => 'keyword'
            ],
            'domainTransfer' => [
                'type' => 'keyword'
            ],
            'meta' => [
                'type' => 'keyword'
            ],
            'typeToken' => [
                'type' => 'keyword'
            ],
            'content' => [
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
