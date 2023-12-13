<?php

namespace TuoiTre\SSO\Traits;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

trait JwtTrait
{
    protected function getJwtFromHeader(string $type = "Bearer"): ?string
    {
        $authorization = request()->header('Authorization');
        if ($authorization && str_starts_with($authorization, $type)) {
            return substr($authorization, 7);
        }
        return null;
    }

    protected function getPayloadFromJwt(string $jwt, string $secretKey, string $algorithm = 'HS256'): ?array
    {
        try {
            return (array)JWT::decode($jwt, new Key($secretKey, $algorithm));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    protected function getPayloadFromJwtNotValidate(?string $jwt): ?array
    {
        $arr = explode('.', (string)$jwt);
        if (count($arr) === 3) {
            return (array)JWT::jsonDecode(JWT::urlsafeB64Decode($arr[1]));
        }
        return null;
    }

    protected function encodeJwt(array $data, string $secretKey, string $algorithm = 'HS256'): string
    {
        return JWT::encode(
            array_merge([
                'iss' => config('app.url', 'SSO Server'),
                'iat' => time(),
            ], $data),
            $secretKey,
            $algorithm
        );
    }
}
