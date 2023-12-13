<?php

namespace App\Modules\Token\Services;

use App\Modules\Token\Helpers\Constants\ConstantDefine;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\RoleRepository;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\AccountRepository;

use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepository;

use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndPointRepository;
use App\Modules\Token\Helpers\ResponseHelper;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Cache;

//use App\Traits\CallAppTrait;
use Carbon\Carbon;
use Common\app\Models\Account;
use Common\App\Models\EndPoint;
use Common\App\Models\Service;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;

class ApiTokenService implements \App\Modules\Token\Services\Interfaces\ApiTokenService
{

    protected $memcached, $url_snot, $auth_snot;

    public function __construct(

        protected ResponseHelper     $responseHelper,
        protected ServiceRepository  $serviceRepository,
        protected EndPointRepository $endPointRepository,
    )
    {

//        $this->memcached = new \Memcached();
//        $this->memcached->addServer(getenv('MEMCACHED_HOST'), getenv('MEMCACHED_PORT'));

    }

    /**
     * @param $data
     * @return mixed
     */
    public function createToken_cache($data)
    {

        $partnerCode = !empty($data['partnerCode']) ? $data['partnerCode'] : null;
        $secretKey = !empty($data['secretKey']) ? $data['secretKey'] : null;


        $check = Cache::get('SV-' . $partnerCode);

        if (!empty($check)) {
            if ($check['status'] == ConstantDefine::ACTIVITY) {
                $point = Cache::get('EP-' . $check['domainTransfer']);

                if (!empty($point) && $point['status'] == ConstantDefine::ACTIVITY) {

                    $private = $point['privateKey'] ?? null;
                    $publicKey = $point['publicKey'] ?? null;
                    $key = env('SECRET_KEY_DATA');

                    $payload['exp'] = time() + ConstantDefine::EXPIRE_JWT;
                    $temp = [
                        'appId' => $check['appId'],
                        'appName' => $check['appName'],
                        'Domain' => $check['domain'],
                        'DomainTransfer' => $check['domainTransfer'],
                        'serveIp' => $check['serveIp'],
                        'serveIpTransfer' => $check['serveIpTransfer'],
                        'typeToken' => $check['typeToken'] ?? null,
                        'secretKey' => $check['secretKey'],
                        'partnerCode' => $check['partnerCode'],
                    ];

                    $payload['data'] = JWT::encode($temp, $key, 'HS256');

                    JWT::$leeway = ConstantDefine::LEEWAY_JWT; // $leeway in seconds

                    $jwt = JWT::encode($payload, $private, 'RS256');

//                  $this->memcached->set($check['domainTransfer'],$jwt,3600);
                    return $this->responseHelper->responseError(true, ConstantDefine::NO_ERROR, $jwt);
                } else {
                    return $this->responseHelper->responseMess('End Point not active', ConstantDefine::REQUEST_TIME_OUT, []);
                }
            } else {
                return $this->responseHelper->responseMess('Service not active', ConstantDefine::SERVICES_OFF, []);
            }

        } else {
            return $this->responseHelper->responseMess('Service not active', ConstantDefine::SERVICES_OFF, []);
        }
    }

    function base64url_encode_array($data)
    {
        // Chuyển đổi mảng thành chuỗi JSON
        $json = json_encode($data);
        // Mã hóa chuỗi JSON bằng base64
        $base64 = base64_encode($json);
        // Thay thế các ký tự không hợp lệ trong base64
        $url = strtr($base64, '+/', '-_');
        // Xóa các ký tự '=' thừa
        $url = rtrim($url, '=');
        // Trả về chuỗi đã mã hóa
        return $url;
    }

    public function verifyToken_cache($data)
    {
        $public = !empty($data['publicKey']) ? $data['publicKey'] : null;
        $jwt = !empty($data['jwt']) ? $data['jwt'] : null;

        $temp = $this->formatPublicKey($public);
        $decodedPublicKey = openssl_pkey_get_public($temp);


        if ($decodedPublicKey != false) {

            try {
                $decoded = JWT::decode($jwt, new Key($temp, 'RS256'));
                $decoded_array = (array)$decoded;
                $decoded_hs256 = (array)$this->decodeHS256($decoded_array['data']);
                $check = Cache::get('SV-' . $decoded_hs256['partnerCode']);


                if (!empty($check) && $check['status'] == ConstantDefine::ACTIVITY) {

                    $tracker = Cache::get('Tracker-EP-' . $decoded_hs256['DomainTransfer']) ?? 0;
                    Cache::put('Tracker-EP-' . $decoded_hs256['DomainTransfer'], $tracker + 1);
                    return $this->responseHelper->responseMess('Verify success', ConstantDefine::NO_ERROR, $decoded_array);
                } else {
                    return $this->responseHelper->responseMess('Service not active', ConstantDefine::REQUEST_TIME_OUT, []);
                }
            } catch (\Exception $e) {
                // Xử lý lỗi
                $status = ConstantDefine::ERROR_ALL;
                $mess = $e->getMessage();
                if ($e->getMessage() == 'Expired token') {
                    $status = ConstantDefine::REQUEST_TIME_OUT;
                }
                if ($e->getMessage() == 'Signature verification failed') {
                    $status = ConstantDefine::SIGNATURE_FALSE;
                    $mess = 'Unauthorized Error';
                }
                return $this->responseHelper->responseMess($mess, $status, []);
            }

        } else {
            return $this->responseHelper->responseMess('Signature failed', ConstantDefine::SIGNATURE_FALSE, []);
        }
    }

    function formatPublicKey($publicKey)
    {
        $string = $publicKey;
        $firstSpacePos = strpos($string, '----- ');
        $lastSpacePos = strrpos($string, ' -----');

        $middleSubstring = '';
        if ($firstSpacePos !== false && $lastSpacePos !== false && $firstSpacePos < $lastSpacePos) {
            $middleSubstring = substr($string, $firstSpacePos + 6, $lastSpacePos - $firstSpacePos - 5);
        }


        // Base64-decode the public key if it's in Base64 format
        $decodedKey = base64_decode($middleSubstring);

        // Check if the key is already in PEM format
        if (strpos($decodedKey, '-----BEGIN PUBLIC KEY-----') !== false) {
            return $publicKey; // Return as is, no need to format again
        }

        // Convert the decoded key to PEM format
        $pemKey = chunk_split(base64_encode($decodedKey), 64, "\n");
        $formattedKey = "-----BEGIN PUBLIC KEY-----\n" . $pemKey . "-----END PUBLIC KEY-----\n";


        return $formattedKey;
    }

    public function decodeHS256($data)
    {
        $jwt = $data;

        $secretKey = env('SECRET_KEY_DATA'); // Replace this with your secret key
        try {

            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function saveInfoService($data)
    {
        $appId = !empty($data['appId']) ? $data['appId'] : null;
        $appName = !empty($data['name']) ? $data['name'] : null;
        $serveIp = !empty($data['serveIp']) ? $data['serveIp'] : null;
        $domain = !empty($data['domain']) ? $data['domain'] : null;
        $serveIpTransfer = !empty($data['serveIpTransfer']) ? $data['serveIpTransfer'] : null;
        $domainTransfer = !empty($data['domainTransfer']) ? $data['domainTransfer'] : null;
        $status = ConstantDefine::ACTIVITY;
        $tempCode = !empty($data['partnerCode']) ? $data['partnerCode'] : null;
        $first_letters = $this->wordSeparator($appName);
        $partnerCode = $tempCode ?? $first_letters . time();
        $code = self::generateCode(12, '', true, true, true);
        $checkservice = $this->serviceRepository->findByAttributes(['domain' => $domain, 'domainTransfer' => $domainTransfer]);

        if (empty($checkservice)) {
            $service = new  Service();
            $service->appId = $appId ?? null;
            $service->appName = $appName ?? null;
            $service->serveIp = $serveIp ?? null;
            $service->domain = $domain ?? null;
            $service->serveIpTransfer = $serveIpTransfer ?? null;
            $service->domainTransfer = $domainTransfer ?? null;
            $service->typeToken = $styleToken ?? null;
            $service->meta = $meta ?? null;
            $service->content = $content ?? null;
            $service->partnerCode = $partnerCode ?? $first_letters . time();
            $service->status = ConstantDefine::PENDING;
            $service->secretKey = $code;
            if ($service->save()) {
                $attribute = [
                    'secretKey' => $service->secretKey,
                    'partnerCode' => $service->partnerCode,
                    'appId' => $service->appId,
                    'appName' => $service->appName,
                    'serveIp' => $service->serveIp,
                    'domain' => $service->domain,
                    'serveIpTransfer' => $service->serveIpTransfer,
                    'domainTransfer' => $service->domainTransfer,
                    'typeToken' => $service->typeToken,
                    'meta' => $service->meta,
                    'content' => $service->content,
                    'status' => $service->status,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'db_id' => $service->getAttribute('id')
                ];
                $saveDB = $this->serviceRepository->create($attribute);
                if ($saveDB) {
                    $arr = [
                        'message' => 'Lưu Service Thành Công',
                        'code' => ConstantDefine::SUCCESS
                    ];
                    return $arr;
                }

            } else {
                Service::query()->where(['id' => $service->getAttribute('id')])->delete();
                $arr = [
                    'message' => 'Lưu Service Không  Thành Công',
                    'code' => ConstantDefine::ERROR_ALL
                ];
                return $arr;
            }
        } else {
            $arr = [
                'message' => ' Partner Code tồn tại',
                'code' => ConstantDefine::ERROR_ALL
            ];
            return $arr;

        }
    }

    public function saveInfoTransfer($data)
    {
        $name = !empty($data['name']) ? $data['name'] : null;
        $serveIp = !empty($data['serveIp']) ? $data['serveIp'] : null;
        $domain = !empty($data['domain']) ? $data['domain'] : null;
        $expire = null;

        $exphours = !empty($data['exphours']) ? $data['exphours'] : null;
        // processing plus hours
//        $temp_exp = strtotime(now()->format('Y-m-d') . ' ' . $exphours);
//        //processing plus days
//        $exp = strtotime('+30 days', $temp_exp);

        $check = $this->endPointRepository->findByAttributes(['domain' => $domain, 'serveIp' => $serveIp]);

        if (empty($check)) {
            $key = $this->renderKey();

            $publicKey = $key['public'] ?? null;
            $privateKey = $key['private'] ?? null;


            $endpoint = new EndPoint();
            $endpoint->publicKey = $publicKey;
            $endpoint->privateKey = $privateKey;
            $endpoint->expire = $expire;
            $endpoint->name = $name;
            $endpoint->status = ConstantDefine::ACTIVITY;
            $endpoint->domain = $domain;
            $endpoint->serveIp = $serveIp;


            if ($endpoint->save()) {
                $attribute = [
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                    'serveIp' => $serveIp,
                    'domain' => $domain,
                    'name' => $name,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'flag' => 'endpoint',
                    'expire' => $expire,
                    'status' => ConstantDefine::ACTIVITY,
//                    'idServices' => $endPoint->idServices
                    'db_id' => $endpoint->getAttribute('id')
                ];

                $saveES = $this->endPointRepository->create($attribute);
                if ($saveES) {
                    $arr = [
                        'message' => 'Lưu Thành Công',
                        'code' => ConstantDefine::SUCCESS
                    ];

                    Cache::put('EP-' . $attribute['domain'], $attribute);
                    return $arr;
                }
            } else {
                $arr = [
                    'message' => 'Lưu Thất bại',
                    'code' => ConstantDefine::ERROR_ALL
                ];
                return $arr;

            }
        } else {
            $arr = [
                'message' => 'End Point Đã tồn tại. ',
                'code' => ConstantDefine::ERROR_ALL
            ];
            return $arr;
        }

    }

    //done
    public function acceptEndPoint($data)
    {

        $id = !empty($data['id']) ? $data['id'] : null;

        $check = $this->pendingRepository->findByAttributes(['_id' => $id, 'flag' => 'endpoint']);

        if (!empty($check)) {
            $endpoint = new EndPoint();
            $endpoint->publicKey = $check['_source']['publicKey'];
            $endpoint->privateKey = $check['_source']['privateKey'];
            $endpoint->expire = $check['_source']['expire'];
            $endpoint->domain = $check['_source']['domain'];
            $endpoint->serveIp = $check['_source']['serveIp'];

            if ($endpoint->save()) {
                $attribute = [
                    'publicKey' => $check['_source']['publicKey'],
                    'privateKey' => $check['_source']['privateKey'],
                    'expire' => $check['_source']['expire'],
                    'domain' => $check['_source']['domain'],
                    'serveIp' => $check['_source']['serveIp'],
                    'db_id' => $endpoint->getAttribute('id')
                ];
                $saveES = $this->endPointRepository->create($attribute);
                if ($saveES) {
                    $this->memcached->set('EP-' . $attribute['domain'], $attribute);
                    $this->pendingRepository->delete(['id' => $id]);
                    return $this->responseHelper->responseMess('Tài khoản End Point thành công', ConstantDefine::NO_ERROR, []);
                }
            } else {
                return $this->responseHelper->responseMess('Lưu End point không thành công ', ConstantDefine::ERROR_ALL, []);
            }
        } else {
            return $this->responseHelper->responseMess('End point không có trong danh sách duyệt ', 44, []);
        }
    }


    public function acceptService($data)
    {

        $id = !empty($data['id']) ? $data['id'] : null;
        $service = Service::query()->find($id);
        if (!empty($service)) {

            $update_mysql = $service->update([
                'status' => ConstantDefine::ACTIVITY,

            ]);
            if ($update_mysql) {
                $attribute = [
                    'status' => ConstantDefine::ACTIVITY,
                    'updated_at' => time(),
                    'db_id' => $service->getAttribute('id')
                ];
                $saveES = $this->serviceRepository->update($attribute);
                if ($saveES) {
                    $attribute = [
                        'secretKey' => $service->getAttribute('secretKey'),
                        'partnerCode' => $service->getAttribute('partnerCode'),
                        'appId' => $service->getAttribute('appId'),
                        'serveIp' => $service->getAttribute('serveIp'),
                        'appName' => $service->getAttribute('appName'),
                        'domain' => $service->getAttribute('domain'),
                        'status' => ConstantDefine::ACTIVITY,
                        'serveIpTransfer' => $service->getAttribute('serveIpTransfer'),
                        'domainTransfer' => $service->getAttribute('domainTransfer'),

                    ];
                    Cache::put('SV-' . $service->getAttribute('partnerCode'), $attribute);
                    $arr = [
                        'message' => 'Lưu Thành Công',
                        'code' => 21
                    ];
                    return $arr;
                }
            } else {
                $arr = [
                    'message' => 'End Point Đã tồn tại. ',
                    'code' => 111
                ];
                return $arr;
            }
        } else {
            $arr = [
                'message' => 'End Point Đã tồn tại. ',
                'code' => 111
            ];
            return $arr;
        }
    }


    public function getListAllEndPoint($data)
    {
        $method = !empty($data['method']) ? $data['method'] : null;
        $type = !empty($data['type']) ? $data['type'] : ConstantDefine::INFO_VIEW;
        $id = !empty($data['id']) ? $data['id'] : null;
        $page = !empty($data['page']) ? $data['page'] : 1;
        $num = !empty($data['number']) ? $data['number'] : 5;
        $dat = [];
        if ($method == 'endpoint') {
            $dat[] = [
                'match' => ['flag' => 'endpoint'],
            ];
        }
        if ($method == 'service') {
            $dat[] = [
                'match' => ['flag' => 'service'],
            ];
        }
        if (!empty($id)) {
            $dat[] = [
                'match' => ['db_id' => $id],
            ];
        }
        $sort = [
            'created_at' => 'desc',
        ];
        $params = [
            'body' => [
                "from" => ($page - 1) * $num,
                "size" => $num,
                'query' => [
                    'bool' => [
                        'must' => $dat
                    ]
                ], 'sort' => $sort
            ]
        ];

        $items = $this->endPointRepository->search($params);

        $result = [];
        if (!empty($items)) {
            $i = 0;

            $hits = count($items['data']);

            $count = $items['total'];

            while ($i < $hits) {
                $result['list'][$i] = $items['data'][$i]['_source'];
                $i++;
            }
        }

        return $result;
    }

    public function delData($data)
    {
        $method = !empty($data['method']) ? $data['method'] : null;
        $id = !empty($data['id']) ? $data['id'] : null;
        if ($method == 1) {
            $client = ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST') . ':9200'])->build();

// Tạo truy vấn xóa
            $params = [
                'index' => 'token_end_points',
                'id' => $id,
            ];

// Thực thi truy vấn
            $response = $client->delete($params);
            if ($response) {
                $del = EndPoint::query()->find($id)->delete();
                if ($del) {
                    return 'xoa thanh cong';
                }
            }

        } else {
                $client = ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST') . ':9200'])->build();

// Tạo truy vấn xóa
                $params = [
                    'index' => 'token_service',
                    'id' => $id,
                ];

// Thực thi truy vấn
                $response = $client->delete($params);
                if ($response) {
                    $del = Service::query()->find($id)->delete();
                    if ($del) {
                        return 'xoa thanh cong';
                    }
                }
            }
    }
    public function serviceaddED($data){
        $id = !empty($data['id']) ? $data['id'] : null;
        $serveIpTransfer = !empty($data['serveIpTransfer']) ? $data['serveIpTransfer'] :null;
        $domainTransfer= !empty($data['domainTransfer']) ? $data['domainTransfer'] : null;
        $check_service=$this->serviceRepository->findByAttributes(['db_id'=>$id]);
        if (!empty($check_service['_source'])){
            $data=[
                'serveIpTransfer'=>$serveIpTransfer,
                    'domainTransfer'=>$domainTransfer,
                'db_id'=>$id
            ];
            $updateES=$this->serviceRepository->update($data);
            if ($updateES){
                $dataSQL=[
                    'serveIpTransfer'=>$serveIpTransfer,
                    'domainTransfer'=>$domainTransfer,

                ];
                $saveSQL=Service::query()->find($id)->update($dataSQL);
                if ($saveSQL) return 'SAVE  thanh cong';
                return 'SAVE khong thanh cong';
            }

        }
        else{
            return 'khong co service';
        }
    }

    public function getListEndPoint($data)
    {

        $method = !empty($data['method']) ? $data['method'] : null;
        $type = !empty($data['type']) ? $data['type'] : ConstantDefine::INFO_VIEW;
        $id = !empty($data['id']) ? $data['id'] : null;
        $page = !empty($data['page']) ? $data['page'] : 1;
        $num = !empty($data['number']) ? $data['number'] : 5;
        $dat = [];
        if ($method == 'endpoint') {
            $dat[] = [
                'match' => ['flag' => 'endpoint'],
            ];
        }
        if ($method == 'service') {
            $dat[] = [
                'match' => ['flag' => 'service'],
            ];
        }
        if (!empty($id)) {
            $dat[] = [
                'match' => ['db_id' => $id],
            ];
        }
        $sort = [
            'created_at' => 'desc',
        ];
        $params = [
            'body' => [
                "from" => ($page - 1) * $num,
                "size" => $num,
                'query' => [
                    'bool' => [
                        'must' => $dat
                    ]
                ], 'sort' => $sort
            ]
        ];

        $items = $this->endPointRepository->search($params);
        $i = 0;

        $hits = count($items['data']);

        $count = $items['total'];

        while ($i < $hits) {
            $result[$i] = $items['data'][$i]['_source'];
            $i++;
        }

        if ($count == $num || $num > $count) {
            $totalpag = 1;
        } else {
            $totalpag = ceil($count / $num);
        }
        if (!empty($result)) {

            $return['list'] = $result;
            $return['pagination']['page'] = $page;
            $return['pagination']['number'] = $num;
            $return['pagination']['total_pages'] = $totalpag;
            $return['pagination']['total_items'] = $items['total'];

            return $return;
        } else {

            return [];
        }


    }

    public function getListService($data)
    {
        $method = !empty($data['method']) ? $data['method'] : null;
        $type = !empty($data['type']) ? $data['type'] : null;
        $id = !empty($data['id']) ? $data['id'] : null;
        $domainTransfer = !empty($data['domainTransfer']) ? $data['domainTransfer'] : null;
        $status = !empty($data['status']) ? $data['status'] : null;
        $serveTransfer = !empty($data['serveTransfer']) ? $data['serveTransfer'] : null;
        $page = !empty($data['page']) ? $data['page'] : 1;
        $num = !empty($data['number']) ? $data['number'] : 5;
        $dat = [];
        $not = [];
        if ($method == 'endpoint') {
            $dat[] = [
                'match' => ['flag' => 'endpoint'],
            ];
        }
        if ($method == 'service') {
            $dat[] = [
                'match' => ['flag' => 'service'],
            ];
        }
        if (!empty($id)) {
            $dat[] = [
                'match' => ['db_id' => $id],
            ];
        }
        if (!empty($domainTransfer)) {
            $dat[] = [
                'match' => ['domainTransfer' => $domainTransfer],
            ];
        }
        if (!empty($serveTransfer)) {
            $dat[] = [
                'match' => ['serveIpTransfer' => $serveTransfer],
            ];
        }
        if (!empty($status)) {
            $dat[] = [
                'match' => ['status' => $status],
            ];
            if ($status == ConstantDefine::PENDING) {
                $not[] = [
                    'match' => ['status' => $status],
                ];
            }
        }
        $sort = [
            'created_at' => 'desc',
        ];
        $params = [
            'body' => [
                "from" => ($page - 1) * $num,
                "size" => $num,
                'query' => [
                    'bool' => [
                        'must' => $dat,
                        'must_not' => $not
                    ]
                ], 'sort' => $sort
            ]
        ];
        if ($type = 'all') {
            $params = [
                'body' => [

                    'query' => [
                        'bool' => [
                            'must' => $dat
                        ]
                    ], 'sort' => $sort
                ]
            ];
        }

        $items = $this->serviceRepository->search($params);

        $i = 0;

        $hits = count($items['data']);

        $count = $items['total'];

        while ($i < $hits) {
            $result[$i] = $items['data'][$i]['_source'];
            $i++;
        }

        if ($count == $num || $num > $count) {
            $totalpag = 1;
        } else {
            $totalpag = ceil($count / $num);
        }
        if (!empty($result)) {

            $return['list'] = $result;
            $return['pagination']['page'] = $page;
            $return['pagination']['number'] = $num;
            $return['pagination']['total_pages'] = $totalpag;
            $return['pagination']['total_items'] = $items['total'];

            return $return;
        } else {

            return [];
        }
    }

    public function updateService($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;
        $domain = !empty($data['domain']) ? $data['domain'] : null;
        $serveIp = !empty($data['serveIp']) ? $data['serveIp'] : null;
        $domainTransfer = !empty($data['domainTransfer']) ? $data['domainTransfer'] : null;
        $serveIpTransfer = !empty($data['serveIpTransfer']) ? $data['serveIpTransfer'] : null;
        $status = !empty('status') ? $data['status'] : null;
        $service = Service::query()->find($id);

        if (!empty($service)) {

            $update_mysql = $service->update([
                'domain' => $domain ?? $service['domain'],
                'serveIp' => $serveIp ?? $service['serveIp'],
                'status' => $status ?? $service['status'],
                'domainTransfer' => $domainTransfer ?? $service['domainTransfer'],
                'serveIpTRansfer' => $serveIpTransfer ?? $service['serveIpTransfer'],
            ]);


            if ($update_mysql) {
                $attribute = [
                    'db_id' => $id,
                    'domain' => $domain ?? $service['domain'],
                    'serveIp' => $serveIp ?? $service['serveIp'],
                    'status' => $status ?? $service['status'],
                    'domainTransfer' => $domainTransfer ?? $service['domainTransfer'],
                    'serveIpTransfer' => $serveIpTransfer ?? $service['serveIpTransfer'],
                ];
                $cache = [
                    'secretKey' => $service['secretKey'],
                    'partnerCode' => $service['partnerCode'],
                    'appId' => $service['appId'],
                    'appName' => $service['appName'],
                    'serveIp' => $serveIp ?? $service['serveIp'],
                    'domain' => $domain ?? $service['domain'],
                    'serveIpTransfer' => $service['serveIpTransfer'],
                    'domainTransfer' => $service['domainTransfer'],
                    'typeToken' => $service['typeToken'],
                    'meta' => $service['meta'],
                    'status' => $status ?? $service['status'],
                    'db_id' => $service->getAttribute('id')
                ];
                $updateEs = $this->serviceRepository->update($attribute);

                Cache::delete('SV-' . $service['partnerCode']);

                Cache::put('SV-' . $service['partnerCode'], $cache);

                return $updateEs;
            } else {
                Log::error('Cập nhật  endpoint không thành công');
                return $this->responseHelper->responseMess('Cập nhật  endpoint không thành công', 44, []);
            }

        } else {
            Log::error('Không có endpoint cần sửa');
            return $this->responseHelper->responseMess('Không có endpoint cần sửa', 44, []);
        }
    }

    public function updateTransfer($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;
        $domain = !empty($data['domain']) ? $data['domain'] : null;
        $serveIp = !empty($data['serveIp']) ? $data['serveIp'] : null;
        $status = !empty('status') ? $data['status'] : null;
        $endpoint = EndPoint::query()->find($id);

        if (!empty($endpoint)) {

            $update_mysql = $endpoint->update([
                'domain' => $domain ?? $endpoint['domain'],
                'serveIp' => $serveIp ?? $endpoint['serveIp'],
                'status' => $status ?? $endpoint['status']
            ]);


            if ($update_mysql) {
                $attribute = [
                    'db_id' => $id,
                    'domain' => $domain ?? $endpoint['domain'],
                    'serveIp' => $serveIp ?? $endpoint['serveIp'],
                    'status' => $status ?? $endpoint['status']
                ];
                $updateEs = $this->endPointRepository->update($attribute);

                $cache = [
                    'publicKey' => $endpoint['publicKey'],
                    'privateKey' => $endpoint['privateKey'],
                    'serveIp' => $serveIp ?? $endpoint['serveIp'],
                    'domain' => $domain ?? $endpoint['domain'],
                    'name' => $endpoint['name'],
                    'expire' => $endpoint['expire'],
                    'status' => $status ?? $endpoint['status'],
//                    'idServices' => $endPoint->idServices
                    'db_id' => $endpoint->getAttribute('id')
                ];
                Cache::delete('EP-' . $endpoint['domain']);
                Cache::put('EP-' . $endpoint['domain'], $cache);

                return $updateEs;
            }
        }
    }

    public function createNewKey($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;

        if (!empty($id))
            $check = $this->endPointRepository->find($id);
        if (!empty($check)) {

            $key = $this->renderKey();

            $timestamp = $check['expire'];
            // chuyen tu timestamp sang y/m/d tach ngay va gio ra
            $formatted_date = date('Y-m-d H:i', strtotime('@' . $timestamp));

            $words = explode(" ", $formatted_date);
            $day = $words[0];
            $hours = $words[1];
            $day_plus = strtotime('+3 days', strtotime($day));

            // xac dinh ngay thuoc thu may
            $dayOfWeekName = Carbon::parse($day)->format('l');

            if ($dayOfWeekName == 'Wednesday') {
                $exp_day = strtotime('+5 days', strtotime($day));

                $mess = 'Hạn sẽ được cộng thêm 5 ngày để thời gian hết hạn tiếp theo trong ngày làm viêc';
            } elseif ($dayOfWeekName == 'Thursday') {
                $exp_day = strtotime('+4 days', strtotime($day));
                $mess = 'Hạn sẽ được cộng thêm 4 ngày để thời gian hết hạn tiếp theo sẽ thời gian làm viêc';
            } else {
                $exp_day = strtotime('+3 days', strtotime($day));
                $mess = 'Hạn sẽ được cộng thêm 3 ngày ';

            }
            $formatted_date = date('Y-m-d', strtotime('@' . $exp_day));
            $exp = strtotime($formatted_date . ' ' . $hours);

            $newKey['db_id'] = $id;
            $newKey['exp_newKey'] = $exp;
            $newKey['newPublicKey'] = $key['public'] ?? null;
            $newKey['newPrivateKey'] = $key['private'] ?? null;
            $update = $this->endPointRepository->update($newKey);

            return response()->json($newKey);

        }
    }

    public function changeNewKey($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;

        $check = $this->endPointRepository->find($id);
        if (!empty($check)) {
            $newKey['id'] = $id;
            $newKey['publicKey'] = $check['newPublicKey'] ?? null;
            $newKey['privateKey'] = $check['newPrivateKey'] ?? null;
            if (!empty($newKey['publicKey']) && $newKey['privateKey']) {
                $update = $this->endPointRepository->update($newKey);
                if ($update) {
                    return $this->responseHelper->responseMess('Cập nhật key mới thành công', ConstantDefine::NO_ERROR, []);

                } else {
                    Log::error('Không có endpoint cần tạo key mới');
                    return $this->responseHelper->responseMess('Cập nhật key mới không thành công', 44, []);
                }
            } else {
                Log::error('Endpoint cần cập nhật chưa có key mới');
                return $this->responseHelper->responseMess('Endpoint cần cập nhật chưa có key mới', 44, []);
            }
        } else {
            Log::error('Không có endpoint cần tạo key mới');
            return $this->responseHelper->responseMess('Không có endpoint cần tạo key mới', 44, []);
        }
    }

    public function extendKey($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;

        $check = EndPoint::query()->find($id);
        if (!empty($check)) {
            $timestamp = $check['expire'];
            // chuyen tu timestamp sang y/m/d tach ngay va gio ra
            $formatted_date = date('Y-m-d H:i', strtotime('@' . $timestamp));
            $words = explode(" ", $formatted_date);
            $day = $words[0];
            $hours = $words[1];
            // xac dinh ngay thuoc thu may
            $dayOfWeekName = Carbon::parse($day)->format('l');

            if ($dayOfWeekName == 'Wednesday') {
                $exp_day = strtotime('+5 days', strtotime($day));
                $mess = 'Hạn sẽ được cộng thêm 5 ngày để thời gian hết hạn tiếp theo trong ngày làm viêc';
            } elseif ($dayOfWeekName == 'Thursday') {
                $exp_day = strtotime('+4 days', strtotime($day));
                $mess = 'Hạn sẽ được cộng thêm 4 ngày để thời gian hết hạn tiếp theo sẽ thời gian làm viêc';
            } else {
                $exp_day = strtotime('+3 days', strtotime($day));
                $mess = 'Hạn sẽ được cộng thêm 3 ngày ';

            }
            $formatted_date = date('Y-m-d', strtotime('@' . $exp_day));
            $exp = strtotime($formatted_date . ' ' . $hours);

            $update_mysql = $check->update([
                'expire' => $exp
            ]);

            if ($update_mysql) {
                $attribute = [
                    'db_id' => $id,
                    'expire' => $exp
                ];
                $updateES = $this->endPointRepository->update($attribute);
                $result = ['Status' => '200 ok',
                    'message' => $mess,
                    'exp' => $exp
                ];
                return $result;
            }
        } else {
            Log::error('Endpoint cần gia hạn key không tồn tại');
            return $this->responseHelper->responseMess('Endpoint cần gia hạn key không tồn tại', 44, []);
        }
    }


    public function acceptKey($data)
    {
        $id = !empty($data['id']) ? $data['id'] : null;
        $check = $this->endPointRepository->find($id);
        $endpoint = EndPoint::query()->find($id);


        if (!empty($check)) {
            $newPublic = $check['newPublicKey'] ?? null;
            $newPrivate = $check['newPrivateKey'] ?? null;
            $newExp = $check['exp_newKey'] ?? null;
            if (!empty($newPrivate) && !empty($newPublic)) {
                $update_mysql = $endpoint->update([
                    'publicKey' => $domain ?? $newPublic,
                    'privateKey' => $serveIp ?? $newPrivate,
                    'expire' => $newExp
                ]);
                $newKey['db_id'] = $id;
                $newKey['expire'] = $newExp;
                $newKey['publicKey'] = $newPublic;
                $newKey['privateKey'] = $newPrivate;
                $newKey['newPublicKey'] = null;
                $newKey['newPrivateKey'] = null;
                $newKey['exp_newKey'] = null;
                $update = $this->endPointRepository->update($newKey);
                if ($update && $update_mysql) {
                    $result = [
                        'message' => 'success',
                        'publicKey' => $newPublic,
                        'exp' => $newExp

                    ];
                    return $result;

                } else {
                    Log::error('Save key không thành công');
                    return $this->responseHelper->responseMess('Không có endpoint cần tạo key mới', 44, []);
                }
            } else {
                $result = ['Status' => '44',
                    'message' => 'Chưa có cặp  key .Hãy tạo cặp key mới',

                ];
                return $result;

            }

        } else {
            Log::error('Không có endpoint cần tạo key mới');
            return $this->responseHelper->responseMess('Không có endpoint cần tạo key mới', 44, []);
        }
    }

    public static function generateCode($amount, $prefix = null, $low = false, $cap = false, $num = false)
    {
        $number = "0123456789";
        $lowercase = "abcdefghijklmnopqrstuvwxyz";
        $capitalize = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $chars = '';
        $code = '';

        if ($low == true) $chars .= $lowercase;
        if ($cap == true) $chars .= $capitalize;
        if ($num == true) $chars .= $number;
        if ($low == false && $cap == false && $num == false) $chars .= $capitalize;

        for ($i = 0; $i < $amount; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $code = !empty($prefix) ? $prefix . $code : $code;
        return $code;
    }

    public function wordSeparator($string)
    {
        $ucwords_string = ucwords($string); // chuyển đổi chữ cái đầu của mỗi từ thành chữ hoa
        $words_array = explode("-", $ucwords_string); // tách chuỗi thành mảng các từ
        $first_letters = "";

        foreach ($words_array as $word) {
            $first_letters .= ucfirst(substr($word, 0, 1)); // lấy ký tự đầu tiên của mỗi từ và nối vào chuỗi kết quả
        }
        return $first_letters;
    }

    public function renderKey()
    {
        // Define the key configuration options
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        // Generate a new private/public key pair
        $res = openssl_pkey_new($config);
        // Extract the private key
        openssl_pkey_export($res, $privateKey);

        // Extract the public key
        $publicKey = openssl_pkey_get_details($res);
        $publicKey = $publicKey["key"];

        return ['public' => $publicKey, 'private' => $privateKey];
    }


}
