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
        return 'token_service';
    }

    public function mapping(): array
    {
        return [
            'id' => [
                'type' => 'long',
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
            'serveIp' => [
                'type' => 'keyword'
            ],
            'domain' => [
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
            'db_id' => [
                'type' => 'integer'
            ],
            'status' => [
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
