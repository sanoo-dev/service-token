<?php

namespace TuoiTre\SSO\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use TuoiTre\SSO\Traits\CryptTrait;

class SocialLoginController extends BaseController
{
    use CryptTrait;

    public function index(Request $request, string $driver): JsonResponse
    {
        $loginUrl = Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
        if (!empty($loginUrl)) {
            $state = $this->encrypt([
                'jwt' => $request->bearerToken(),
                'client_state' => $request->post('client_state'),
                'redirect_url' => $request->post('redirect_url'),
                'client_info' => $this->getClientInfoFromRequest($request)
            ]);
            $loginUrl .= "&state=$state";
            return $this->responseSuccess([
                'message' => __('sso::messages.success'),
                'data' => [
                    'login_url' => $loginUrl
                ]
            ]);
        }
        return $this->responseError([
            'message' => __('sso::messages.otherError'),
            'data' => null
        ]);
    }
}