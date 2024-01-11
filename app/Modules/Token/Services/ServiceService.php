<?php

namespace App\Modules\Token\Services;

use App\Modules\Token\Helpers\Constants\ConstantDefine;
use App\Modules\Token\Helpers\Constants\MessageResponseCode;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepositoryInterface;
use App\Modules\Token\Services\Interfaces\ServiceServiceInterface;
use Carbon\Carbon;
use Common\App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class ServiceService implements ServiceServiceInterface
{
    /**
     * @param ResponseHelperInterface $responseHelper
     * @param ServiceRepositoryInterface $serviceRepository
     */
    public function __construct(
        protected ResponseHelperInterface    $responseHelper,
        protected ServiceRepositoryInterface $serviceRepository,
    )
    {
    }

    /**
     * @param array $search
     * @return array
     */
    public function getOneService(array $search = []): array
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

        $response = $this->serviceRepository->search($params);

        if (!empty($response['data'][0]['_source'])) {
            return [
                'message' => 'Chi tiết Service',
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
    public function getListService(int $currentPage = 1, int $perPage = 5, array $search = [], array $conditions = []): array
    {
        $must = [];

        if (!empty($search['id'])) $must[] = ['match' => ['db_id' => $search['id']]];
        if (!empty($search['name'])) $must[] = ['match' => ['name' => $search['domain']]];
        if (!empty($search['domain'])) $must[] = ['match' => ['domain' => $search['domain']]];
        if (!empty($search['server_ip'])) $must[] = ['match' => ['server_ip' => $search['server_ip']]];
        if (!empty($search['endpoint_domain'])) $must[] = ['match' => ['endpoint_domain' => $search['endpoint_domain']]];
        if (!empty($search['endpoint_server_ip'])) $must[] = ['match' => ['endpoint_server_ip' => $search['endpoint_server_ip']]];
        if (!empty($search['status'])) $must[] = ['match' => ['status' => $search['status']]];

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

        $response = $this->serviceRepository->search($params);

        if (!empty($response['data'])) {
            $results = [];
            foreach ($response['data'] as $item) {
                $results[] = $item['_source'];
            }

            return [
                'message' => 'Danh sách Service.',
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
     * @throws InvalidArgumentException
     */
    public function update(array $data, $id): array
    {
        $service = Service::query()->find($id);

        if (empty($service)) {
            return [
                'message' => 'Service không tồn tại.',
                'status' => MessageResponseCode::MESSAGE_NOT_FOUND,
                'data' => [],
            ];
        }

        $updateDb = $service->update($data);

        if ($updateDb) {
            $esData = $service->getAttributes();
            $esData['created_at'] = Carbon::parse($service['created_at'])->timestamp;
            $esData['updated_at'] = Carbon::parse($service['updated_at'])->timestamp;
            $esData['db_id'] = $service['id'];

            $updateEs = $this->serviceRepository->update($esData);

            if ($updateEs) {
                Cache::delete('services:SV-' . $service['partner_code']);
                Cache::put('services:SV-' . $service['partner_code'], $esData);

                return [
                    'message' => 'Cập nhật Service thành công.',
                    'status' => MessageResponseCode::MESSAGE_SUCCESS,
                    'data' => [],
                ];
            }
        }

        return [
            'message' => 'Cập nhật Service thất bại.',
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
        if ($this->isExisted($data['domain'], $data['endpoint_domain'])) {
            return [
                'message' => 'Service đã tồn tại!',
                'status' => MessageResponseCode::MESSAGE_DUPLICATE_DATA,
                'data' => [],
            ];
        }

        // Handle data before store
        $data['status'] = ConstantDefine::PENDING;
        $data['partner_code'] = $this->generatePartnerCode($data['name']);
        $data['secret_key'] = $this->generateSecretKey(12);

        // Save DB
        $service = Service::query()->create($data);

        if ($service) {
            // Handle es data before sync
            $esData = $service->attributesToArray();
            $esData['created_at'] = Carbon::parse($service['created_at'])->timestamp;
            $esData['updated_at'] = Carbon::parse($service['updated_at'])->timestamp;
            $esData['db_id'] = $service['id'];

            // Sync db to es
            $syncToElasticsearch = $this->serviceRepository->create($esData);

            if ($syncToElasticsearch) {
                return [
                    'message' => 'Tạo Service thành công!',
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

    /**
     * @param string $domain
     * @param string $endpointDomain
     * @return bool
     */
    private function isExisted(string $domain, string $endpointDomain): bool
    {
        $service = $this->serviceRepository->findByAttributes(['domain' => $domain, 'endpoint_domain)' => $endpointDomain]);

        return !empty($service);
    }

    /**
     * @param string $string
     * @return string
     */
    private function concatenateInitials(string $string): string
    {
        $words = preg_split('/[^a-zA-Z0-9]+/', $string);
        $acronym = '';

        foreach ($words as $word) {
            $acronym .= strtoupper(substr($word, 0, 1));
        }

        return $acronym;
    }

    /**
     * @param string $name
     * @return string
     */
    private function generatePartnerCode(string $name): string
    {
        return $this->concatenateInitials($name) . time();
    }

    /**
     * @param int $codeLength
     * @param bool|null $prefix
     * @param bool $low
     * @param bool $cap
     * @param bool $num
     * @return string
     * @throws \Random\RandomException
     */
    private function generateSecretKey(int $codeLength, bool $prefix = null, bool $low = false, bool $cap = false, bool $num = false): string
    {
        $number = '0123456789';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $capitalize = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $chars = '';
        if ($low) $chars .= $lowercase;
        if ($cap) $chars .= $capitalize;
        if ($num) $chars .= $number;
        if (!$low && !$cap && !$num) $chars .= $capitalize;

        $secretKey = '';

        while (strlen($secretKey) < $codeLength) {
            $randomBytes = random_bytes(1);
            $randomInt = hexdec(bin2hex($randomBytes));
            $randomIndex = $randomInt % strlen($chars);
            $secretKey .= $chars[$randomIndex];
        }

        return ($prefix !== null) ? $prefix . $secretKey : $secretKey;
    }
}
