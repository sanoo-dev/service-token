<?php

namespace App\Modules\Auth\Http\Middleware;

use App\Modules\Auth\Helpers\Constants\ConstantDefine;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class  CheckCookie
{
    public function __construct(protected AccountRepository $accountRepository)
    {
        $this->redis_account = Redis::connection('account')->client();
        $this->redis = Redis::connection('route')->client();
    }

    public function handle($request, Closure $next)
    {

        $cookie_erp = \Cookie::get('_ttoauth_prod');
        if (empty($cookie_erp)) {
            $back_url = env('URL_ERP_LOGIN') . env('URL_TOKEN');

            return redirect($back_url);
        }

        $curl = $this->curlData($cookie_erp);

        if (!empty($curl['data'])) {
            $user_dep_pos = $curl['data']['user_dep_pos'][0]['parent']['name'] ?? null;
            $fullname=$curl['data']['full_name'] ?? null;
            $words = explode(" ", $fullname);
            $name1 = $words[count($words)-2];
            $name2 = $words[count($words)-1];
            $name=$name1.' '.$name2;
            $email = $curl['data']['email'] ?? null;
            $slug = $this->textToSlug($user_dep_pos);
            // check authorization
            $da = $this->checkAuth($email, $slug,$name);

            if ($da['message'] == 'oke') {
                return $next($request);
            } else {
                Session::flash('alert', 'Bạn không có quyền truy cập Service Token!');
                return redirect( 'http://token.tuoitre.vn/token/viewWelcome');
            }
        } else {
            if (!empty($curl['error'])) {
                return $curl;
            }


            return $next($request);
        }

    }

    public function curlData($cookie_erp)
    {

        $auth = $this->parseToken($cookie_erp);

        if (!empty($auth)) {
            if (!empty($auth['access_token'])) {

                try {
                    $ac = $auth['access_token'];

                    $url = env('URL_PARSE_TOKEN');

                    $client=new Client([
                        'headers'=>[
                            'X-Custom-Header'=>'Value',
                            'Authorization'=>'Bearer '.$ac
                        ]
                    ]);

                    $response=$client->get($url);
                    $data=json_decode($response->getBody()->getContents(),true);

                    if (!empty($data['data'])) {
                        return $data;
                    }
                    $data['error'] = $data;
                    return $data;
                } catch (RequestException $exception) {
                    if ($exception->getCode() === CURLE_OPERATION_TIMEOUTED) {
                        // Nếu là lỗi timeout, bỏ qua yêu cầu
                        echo "Request was skipped due to timeout.";
                    } else {
                        // Xử lý các lỗi khác nếu cần
                        echo "An error occurred: " . $exception->getMessage();
                    }
                }
            } else {
                $result = [
                    'message' => 'Access token not found',
                    'data' => []
                ];
                return $result;
            }

        } else {
            $result = [
                'message' => 'Api no data',
                'data' => []
            ];
            return $result;
        }


    }

    public function parseToken($token)
    {

        try {

            $base64Url = explode('.', $token)[1];

            $base64 = str_replace(['-', '_'], ['+', '/'], $base64Url);
            $decodedPayload = base64_decode($base64);


            $payload = json_decode($decodedPayload, true);


            return $payload;
        } catch (\Exception $e) {

        }
    }

    function textToSlug($text)
    {
        // Remove diacritics from Vietnamese characters
        $slug = normalizer_normalize($text, \Normalizer::FORM_D);
        $slug = preg_replace('/[\x{0300}-\x{036f}]/u', '', $slug);

        // Replace special characters and spaces with hyphens
        $normalizedSlug = preg_replace('/[^\w\s-]/u', '', $slug);
        $normalizedSlug = strtolower($normalizedSlug);
        $normalizedSlug = preg_replace('/\s+/u', '-', $normalizedSlug);

        return $normalizedSlug;
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
