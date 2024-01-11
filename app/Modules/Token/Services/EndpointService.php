<?php

namespace App\Modules\Token\Services;

use App\Modules\Token\Helpers\Constants\ConstantDefine;
use App\Modules\Token\Helpers\Constants\MessageResponseCode;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndpointRepositoryInterface;
use App\Modules\Token\Services\Interfaces\EndpointServiceInterface;
use Carbon\Carbon;
use Common\App\Models\Endpoint;
use Illuminate\Support\Facades\Cache;

class EndpointService implements EndpointServiceInterface
{
    /**
     * @param ResponseHelperInterface $responseHelper
     * @param EndpointRepositoryInterface $endpointRepository
     */
    public function __construct(
        protected ResponseHelperInterface $responseHelper,
        protected EndpointRepositoryInterface $endpointRepository,
    )
    {
    }

    /**
     * @param array $search
     * @return array
     */
    public function getOneEndpoint(array $search = []): array
    {
        $must = [];

        if (!empty($search['id'])) $must[] = ['match' => ['db_id' => $search['id']]];
        if (!empty($search['name'])) $must[] = ['match' => ['name' => $search['name']]];
        if (!empty($search['domain'])) $must[] = ['match' => ['domain' => $search['domain']]];
        if (!empty($search['server_ip'])) $must[] = ['match' => ['server_ip' => $search['server_ip']]];

        $params = [
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                'sort' => [
                    'created_at' => 'desc',
                ],
                'size' => 1,
            ]
        ];

        $response = $this->endpointRepository->search($params);

        if (!empty($response['data'][0]['_source'])) {
            return [
                'message' => 'Chi tiết Endpoint',
                'status' => MessageResponseCode::MESSAGE_SUCCESS,
                'data' => $response['data'][0]['_source'],
            ];
        }

        return [
            'message' => 'Không có dữ liệu.',
            'status' => MessageResponseCode::MESSAGE_NOT_FOUND,
            'data' => [],
        ];
    }

    /**
     * @param int $currentPage
     * @param int $perPage
     * @param array $search
     * @return array
     */
    public function getListEndpoint(int $currentPage, int $perPage, array $search = [], array $conditions = []): array
    {
        $must = [];

        if (!empty($search['id'])) $must[] = ['match' => ['db_id' => $search['id']]];
        if (!empty($search['name'])) $must[] = ['match' => ['name' => $search['name']]];
        if (!empty($search['domain'])) $must[] = ['match' => ['domain' => $search['domain']]];
        if (!empty($search['server_ip'])) $must[] = ['match' => ['server_ip' => $search['server_ip']]];

        $params = [
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                'sort' => [
                    'created_at' => 'desc',
                ],
                'from' => ($currentPage - 1) * $perPage,
                'size' => $perPage,
            ]
        ];

        if (!empty($conditions)) {
            foreach ($conditions as $key => $condition) {
                $params['body']['query']['bool'][$key] = $condition;
            }
        }

        $response = $this->endpointRepository->search($params);

        if (!empty($response['data'])) {
            $results = [];
            foreach ($response['data'] as $item) {
                $results[] = $item['_source'];
            }

            return [
                'message' => 'Danh sách Endpoint.',
                'status' => MessageResponseCode::MESSAGE_SUCCESS,
                'data' => $results,
            ];
        }

        return [
            'message' => 'Không có dữ liệu.',
            'status' => MessageResponseCode::MESSAGE_NOT_FOUND,
            'data' => [],
        ];
    }

    /**
     * @param array $data
     * @param $id
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function update(array $data, $id): array
    {
        $endpoint = Endpoint::query()->find($id);

        if (empty($endpoint)) {
            return [
                'message' => 'Endpoint không tồn tại',
                'status' => MessageResponseCode::MESSAGE_NOT_FOUND,
                'data' => [],
            ];
        }

        $updateDb = $endpoint->update($data);

        if ($updateDb) {
            $esData = $endpoint->getAttributes();
            $esData['created_at'] = Carbon::parse($endpoint['created_at'])->timestamp;
            $esData['updated_at'] = Carbon::parse($endpoint['updated_at'])->timestamp;
            $esData['db_id'] = $endpoint['id'];

            $updateEs = $this->endpointRepository->update($esData);

            if ($updateEs) {
                Cache::delete('endpoints:EP-' . $endpoint['domain']);
                Cache::put('endpoints:EP-' . $endpoint['domain'], $esData);

                return [
                    'message' => 'Cập nhật Endpoint thành công.',
                    'status' => MessageResponseCode::MESSAGE_SUCCESS,
                    'data' => [],
                ];
            }
        }

        return [
            'message' => 'Cập nhật Endpoint thất bại.',
            'status' => MessageResponseCode::MESSAGE_GENERAL_ERROR,
            'data' => [],
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        // Check existed
        if ($this->isExisted($data['domain'], $data['server_ip'])) {
            return [
                'message' => 'Endpoint đã tồn tại!',
                'status' => MessageResponseCode::MESSAGE_DUPLICATE_DATA,
                'data' => [],
            ];
        }

        $keyPair = $this->generateKeyPair();

        if (!$keyPair) return [
            'message' => 'Không tạo được keypair!',
            'status' => MessageResponseCode::MESSAGE_GENERAL_ERROR,
            'data' => [],
        ];

        // Handle data before store
        $data['private_key'] = $keyPair['private'];
        $data['public_key'] = $keyPair['public'];
        $data['status'] = ConstantDefine::ACTIVITY;

        // Save DB
        $endpoint = Endpoint::query()->create($data);

        if ($endpoint) {
            // Handle es data before sync
            $esData = $endpoint->getAttributes();
            $esData['created_at'] = Carbon::parse($endpoint['created_at'])->timestamp;
            $esData['updated_at'] = Carbon::parse($endpoint['updated_at'])->timestamp;
            $esData['db_id'] = $endpoint['id'];

            // Sync db to es
            $syncToElasticsearch = $this->endpointRepository->create($esData);

            if ($syncToElasticsearch) {
                Cache::put('endpoints:EP-' . $endpoint['domain'], $endpoint->getAttributes());

                return [
                    'message' => 'Tạo Endpoint thành công.',
                    'status' => MessageResponseCode::MESSAGE_SUCCESS,
                    'data' => [],
                ];
            } else {
                return [
                    'message' => 'Đồng bộ elasticsearch thất bại!',
                    'status' => MessageResponseCode::MESSAGE_DATA_NOT_SAVED,
                    'data' => [],
                ];
            }
        } else {
            return [
                'message' => 'Lưu trữ database thất bại!',
                'status' => MessageResponseCode::MESSAGE_DATA_NOT_SAVED,
                'data' => [],
            ];
        }
    }

    // Private

    /**
     * @return bool|array
     */
    private function generateKeyPair(): bool|array
    {
        $config = [
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Generate a new private/public key pair
        $response = openssl_pkey_new($config);

        if (!$response) {
            return false;
        }

        $privateKey = '';

        // Extract the private key
        if (!openssl_pkey_export($response, $privateKey)) {
            return false;
        }

        // Extract the public key
        $publicKeyDetails = openssl_pkey_get_details($response);

        if ($publicKeyDetails === false || !isset($publicKeyDetails["key"])) {
            return false;
        }

        $publicKey = $publicKeyDetails["key"];

        return ['public' => $publicKey, 'private' => $privateKey];
    }

    /**
     * @param string $domain
     * @param string $serverIp
     * @return bool
     */
    private function isExisted(string $domain, string $serverIp): bool
    {
        $endpoint = $this->endpointRepository->findByAttributes(['domain' => $domain, 'serve_ip' => $serverIp]);

        return !empty($endpoint);
    }
}
