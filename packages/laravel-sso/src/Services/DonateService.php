<?php

namespace TuoiTre\SSO\Services;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use TuoiTre\SSO\Constants\ResponseTypeConstant;
use TuoiTre\SSO\Services\Interfaces\BrokerService;
use TuoiTre\SSO\Services\Interfaces\MemberService;
use TuoiTre\SSO\Services\Interfaces\CacheService;
use TuoiTre\SSO\Services\Interfaces\DonateService as DonateServiceInterface;
use TuoiTre\SSO\Traits\BrokerSessionTrait;
use TuoiTre\SSO\Traits\JwtTrait;
use TuoiTre\SSO\Traits\ReturnTrait;
use TuoiTre\SSO\Traits\SessionTrait;
use TuoiTre\SSO\Traits\TrackingTrait;
use Illuminate\Support\Facades\Cookie;

class DonateService implements DonateServiceInterface
{

    use BrokerSessionTrait;
    use JwtTrait;
    use ReturnTrait;
    use SessionTrait;
    use TrackingTrait;


    /**
     * @param BrokerService $brokerService
     * @param MemberService $memberService
     * @param CacheService $cacheService
     */
    public function __construct(
        protected BrokerService $brokerService,
        protected MemberService $memberService,
        protected CacheService $cacheService
    ) {
        $this->setRedisConnection(config('laravel-sso.redisConnection', 'default'));
    }

    private function getMemberIdFromBrokerSession()
    {
        $jwt = $this->getJwtFromHeader();
        if (!empty($jwt)) {
            $payloadNotValidate = $this->getPayloadFromJwtNotValidate($jwt);
            if (!empty($payloadNotValidate['token'])) {
                $sessionData = $this->getBrokerSessionData($payloadNotValidate['token']);
                if (!empty($sessionData['memberId'])) {
                    return $sessionData['memberId'];
                }
            }
        }
        return null;
    }

    public function donate(string $numberOfStars, string $articleLink): ?array
    {
        $memberId = $this->getMemberIdFromBrokerSession();
        if (!$memberId) {
            return $this->returnArrError([
                'message' => __('sso::messages.auth.notLogin'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
        }

        $result = $this->memberService->donate([
            'member_id' => $memberId,
            'number_of_stars' => $numberOfStars,
            'article_link' => $articleLink
        ]);

        if(empty($result['success'])){
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
            ]);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? []
        ]);
    }

    public function transferStar(
        string $paymentType,
        string $memberId,
        string $appId,
        string $star,
        ?string $typeInfo,
        
        ?string $phoneOrEmail,

        ?string $toMemberId,
        ?string $articleLink,
        ?string $commentContent,
        ?string $name,
        string $type,

        string $otp = null
    ): ?array
    {
        $result = $this->memberService->transferStar(
            $paymentType, 
            $memberId, 
            $appId, 
            $star, 
            $typeInfo,

            $phoneOrEmail,

            $toMemberId, 
            $articleLink, 
            $commentContent,
            $name, 
            $type,

            $otp
        );
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.transfer.transferFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['errors'] ?? __('sso::messages.transfer.transferFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
        }
        return $this->returnArrSuccess([
            'data' => $result['data'] ?? []
        ]);
    }

    public function OTPTransfer(
        string $memberId,
        string $type,
        string $typeInfo,
        ?string $phoneOrEmail,
        ?string $toMemberId,
        ?string $name,
        ?string $star,
        ?string $articleLink,
        ?string $commentContent,
    ): ?array
    {
        $result = $this->memberService->OTPTransfer(
            $memberId,
            $type,
            $typeInfo,
            $phoneOrEmail,
            $toMemberId,
            $name,
            $star,
            $articleLink,
            $commentContent,
        );
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed'),
                ],
                'data' => [
                    'timeout' => $result['data']['timeout'] ?? null,
                    'to_member_id' => $result['data']['to_member_id'] ?? null
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }
}
