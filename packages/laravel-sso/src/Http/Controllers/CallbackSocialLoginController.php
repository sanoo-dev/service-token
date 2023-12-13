<?php

namespace TuoiTre\SSO\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use TuoiTre\SSO\Constants\ResponseTypeConstant;
use TuoiTre\SSO\Services\Interfaces\SSOServerService;
use TuoiTre\SSO\Traits\CryptTrait;

class CallbackSocialLoginController extends BaseController
{
    use CryptTrait;
    public function __construct(
        protected SSOServerService $SSOServerService
    ) {
    }

    public function index(Request $request, string $driver): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (empty($stateData = $this->decrypt($request->get('state', '')))) {
            return $this->responseError([
                'message' => __('sso::messages.auth.loginFailed'),
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_PARAMS_INVALID
            ]);
        }

        try {
            if (!empty($stateData['jwt'])) {
                $request->headers->set('Authorization', 'Bearer ' . $stateData['jwt']);
            }
            /**
             * @var User $socialInfo
             */
            $socialInfo = Socialite::driver($driver)->stateless()->user();
            $this->SSOServerService->setTrackingData($stateData['client_info']);
            $result = $this->SSOServerService->socialLogin([
                'email' => $socialInfo->getEmail(),
                'name' => $socialInfo->getName(),
                'social_provider' => $driver,
                'social_id' => $socialInfo->getId(),
                'avatar' => $socialInfo->getAvatar(),
                'data' => collect($socialInfo)->toArray()
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error("Social login is canceled or failed");
        }

        $query = parse_url($stateData['redirect_url'], PHP_URL_QUERY);
        if (!empty($query)) {
            $stateData['redirect_url'] .= '&';
        } else {
            $stateData['redirect_url'] .= '?';
        }

        if (!empty($result['data']['token'])) {
            $customQuery = "success=true&token=" . $result['data']['token'];
        } else {
            $customQuery = "success=false";
        }
        return redirect($stateData['redirect_url'] . "$customQuery&state=" . ($stateData['client_state'] ?? null));
    }
}