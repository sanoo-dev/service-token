<?php

namespace App\Modules\Auth\Http\Middleware;

use App\Modules\Auth\Helpers\Constants\ConstantDefine;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository;
use Common\App\Traits\CallApiTrait;
use Closure;
use Illuminate\Support\Facades\Redis;

class  CheckCookie
{
    use CallApiTrait;

    public function __construct(protected AccountRepository $accountRepository)
    {
        $this->redis_account = Redis::connection('account')->client();
        $this->redis = Redis::connection('route')->client();
    }

    public function handle($request, Closure $next)
    {
        $cookieERP = \Cookie::get('_ttoauth_prod');

        $auth = $this->getAuth($cookieERP);

        if (empty($auth)) return false;

        if ($auth['user_dep_pos'][0]['parent']['name'] === 'Phòng Công nghệ Thông tin') return $next($request);

        return false;
    }

    private function getAuth(string $cookieERP): array
    {
        // Decode JWT Token
        $jwtDecoded = $this->decodeJWTTokenPayload($cookieERP);

        // Check access token
        if (empty($jwtDecoded['access_token'])) return [];

        // Curl to get auth with access token
        $url = env('URL_PARSE_TOKEN');
        $parameters = [];
        $headers = [
            'X-Custom-Header'=> 'Value',
            'Authorization'=>'Bearer ' . $jwtDecoded['access_token'],
        ];

        $auth = $this->call('GET', $url, $parameters, $headers);

        // Handle result
        if (!empty($auth) && $auth['success'] && !empty($auth['data'])) {
            return $auth['data'];
        }

        return [];
    }

    private function decodeJWTTokenPayload($token)
    {
        try {
            $base64Url = explode('.', $token)[1];
            $base64 = str_replace(['-', '_'], ['+', '/'], $base64Url);
            $decodedPayload = base64_decode($base64);
            return json_decode($decodedPayload, true);
        } catch (\Exception $e) {
            \Log::error('Error decoding JWT token payload: ' . $e->getMessage());
            return null;
        }
    }

    public function checkAuth($email, $slug,$name)
    {
        $check = $this->accountRepository->findByAttributes(['email' => $email]);
        if ($slug != 'phong-cong-nghe-thong-tin' || empty($email) || empty($check)) {
            $result = [
                'message' => 'no',
                'data' => []
            ];
            return $result;
        }
        $check = $this->accountRepository->findByAttributes(['email' => $email]);
        if (empty($check)) {
            $result = [
                'message' => 'no',
                'data' => []
            ];
            return $result;
        }
        // Luu cache redis account
        $data_redis = $check['_source']['id_role'];

        $this->redis_account->set('account::' . $check['_source']['guid'], $data_redis);
        //luu cookie id của user định danh

        $ab = setcookie('_IDT', $check['_source']['guid'], time() + ConstantDefine::EXPIRE_COOKIE_ACCOUNT, '/', '.tuoitre.vn');
        $ab = setcookie('_info', $name, time() + ConstantDefine::EXPIRE_COOKIE_ACCOUNT, '/', '.tuoitre.vn');
        $result = [
            'message' => 'oke',
            'data' => []
        ];
        return $result;
    }


}
