<?php

namespace App\Modules\Token\Services;

use App\Modules\Token\Helpers\Constants\ConstantDefine;
use App\Modules\Token\Helpers\Constants\MessageResponseCode;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Services\Interfaces\TokenServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use stdClass;

class TokenService implements TokenServiceInterface
{
    /**
     * @param ResponseHelperInterface $responseHelper
     */
    public function __construct(
        protected ResponseHelperInterface $responseHelper,
    )
    {
    }

    /**
     * @param array $data
     * @return array
     */
    public function generate(array $data): array
    {
        $privateKeyFormatted = $this->formatPrivateKey($data['private_key']);

        $payload['partner_code'] = $data['partner_code'];
        $payload['exp'] = time() + ConstantDefine::EXPIRE_JWT;

        JWT::$leeway = ConstantDefine::LEEWAY_JWT;

        // Encode with RS256
        if (!empty($privateKeyFormatted)) {
            $token = JWT::encode($payload, $privateKeyFormatted, 'RS256');
            return [
                'message' => 'Tạo Token thành công.',
                'status' => MessageResponseCode::MESSAGE_SUCCESS,
                'data' => $token,
            ];
        }

        return [
            'message' => 'Tạo Token thất bại.',
            'status' => MessageResponseCode::MESSAGE_GENERAL_ERROR,
            'data' => '',
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function verify($data): array
    {
        // Get payload from token
        $jwtTokenPayload = $this->decodeJWTTokenPayload($data['token']);

        // Check expire
        if ($jwtTokenPayload['exp'] < time())
            return [
                'message' => 'Token hết hạn.',
                'status' => MessageResponseCode::MESSAGE_REQUEST_TIMEOUT,
                'data' => [],
            ];

        // Get service with partner code
        $service = Cache::get('services:SV-' . $jwtTokenPayload['partner_code']);
        $isInvalidService = $this->isInvalidService($service);
        if (!empty($isInvalidService)) return $isInvalidService;

        // Get endpoint from service
        $endpoint = Cache::get('endpoints:EP-' . $service['endpoint_domain']);
        $isInvalidEndpoint = $this->isInvalidEndpoint($endpoint);
        if (!empty($isInvalidEndpoint)) return $isInvalidEndpoint;

        // Get public key
        $publicKeyFormatted = $endpoint['public_key'];

        // Verify signature with RS256
        try {
            $this->decodeRS256($data['token'], $publicKeyFormatted);

            return [
                'message' => 'Xác thực thành công.',
                'status' => MessageResponseCode::MESSAGE_SUCCESS,
                'data' => [],
            ];
        } catch (\Exception $ex) {
            $status = MessageResponseCode::MESSAGE_GENERAL_ERROR;

            switch ($ex->getMessage()) {
                case 'Expired token':
                    $message = 'Token hết hạn.';
                    $status = MessageResponseCode::MESSAGE_REQUEST_TIMEOUT;
                    break;
                case 'Syntax error, malformed JSON':
                    $message = 'Sai cú pháp.';
                    $status = MessageResponseCode::MESSAGE_SYNTAX_ERROR;
                    break;
                case 'Signature verification failed':
                    $message = 'Xác minh chữ ký thất bại.';
                    $status = MessageResponseCode::MESSAGE_SIGNATURE_FAILED;
                    break;
                default:
                    $message = 'Lỗi chưa xác định. Chi tiết: ' . $ex->getMessage();
                    break;
            }

            return [
                'message' => $message,
                'status' => $status,
                'data' => [],
            ];
        }
    }

    /**
     * @param $token
     * @return array
     */
    private function decodeJWTTokenPayload($token): array
    {
        try {
            $base64Url = explode('.', $token)[1];
            $base64 = str_replace(['-', '_'], ['+', '/'], $base64Url);
            $decodedPayload = base64_decode($base64);
            return json_decode($decodedPayload, true);
        } catch (\Exception $e) {
            \Log::error('Error decoding JWT token payload: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @param $token
     * @param $publicKey
     * @return stdClass
     */
    private function decodeRS256($token, $publicKey): stdClass
    {
        return JWT::decode($token, new Key($publicKey, 'RS256'));
    }

    /**
     * @param $token
     * @param $secretKey
     * @return stdClass
     */
    private function decodeHS256($token, $secretKey): stdClass
    {
        return JWT::decode($token, new Key($secretKey, 'HS256'));
    }

    /**
     * @param $publicKey
     * @return string
     */
    private function formatPublicKey($publicKey): string
    {
        return "-----BEGIN PUBLIC KEY-----\n" . wordwrap(str_replace(['-----BEGIN PUBLIC KEY----- ', ' -----END PUBLIC KEY-----'], '', $publicKey), 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }

    /**
     * @param $privateKey
     * @return string
     */
    private function formatPrivateKey($privateKey): string
    {
        return "-----BEGIN PRIVATE KEY-----\n" . wordwrap(str_replace(['-----BEGIN PRIVATE KEY----- ', ' -----END PRIVATE KEY-----'], '', $privateKey), 64, "\n", true) . "\n-----END PRIVATE KEY-----";
    }

    /**
     * @param array $service
     * @return array
     */
    private function isInvalidService(array $service): array
    {
        if (empty($service))
            return [
                'message' => 'Service không tồn tại.',
                'status' => MessageResponseCode::MESSAGE_SERVICES_OFFLINE,
                'data' => [],
            ];

        if ($service['status'] != ConstantDefine::ACTIVITY)
            return  [
                'message' => 'Service không hoạt động.',
                'status' => MessageResponseCode::MESSAGE_SERVICES_OFFLINE,
                'data' => [],
            ];

        return [];
    }

    /**
     * @param array $endpoint
     * @return array
     */
    private function isInvalidEndpoint(array $endpoint): array
    {
        if (empty($endpoint))
            return [
                'message' => 'Endpoint không tồn tại.',
                'status' => MessageResponseCode::MESSAGE_SERVICES_OFFLINE,
                'data' => [],
            ];

        if ($endpoint['status'] != ConstantDefine::ACTIVITY)
            return  [
                'message' => 'Endpoint không hoạt động.',
                'status' => MessageResponseCode::MESSAGE_SERVICES_OFFLINE,
                'data' => [],
            ];

        return [];
    }
}
