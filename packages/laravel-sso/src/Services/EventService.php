<?php

namespace TuoiTre\SSO\Services;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use TuoiTre\SSO\Constants\ResponseTypeConstant;
use TuoiTre\SSO\Services\Interfaces\BrokerService;
use TuoiTre\SSO\Services\Interfaces\MemberService;
use TuoiTre\SSO\Services\Interfaces\CacheService;
use TuoiTre\SSO\Services\Interfaces\EventService as EventServiceInterface;
use TuoiTre\SSO\Traits\BrokerSessionTrait;
use TuoiTre\SSO\Traits\JwtTrait;
use TuoiTre\SSO\Traits\ReturnTrait;
use TuoiTre\SSO\Traits\SessionTrait;
use TuoiTre\SSO\Traits\TrackingTrait;
use Illuminate\Support\Facades\Cookie;

class EventService implements EventServiceInterface
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

    private function preparedToCallMember()
    {
        $this->memberService->setTrackingData($this->getTrackingData());
    }

    public function buyTicket(string $id, string $codeEvent, string $name, string $email, string $phone): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->buyTicket($id, $codeEvent, $name, $email, $phone);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.event.buyTicketFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.event.buyTicketFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
        }
        return $this->returnArrSuccess([
            'data' => $result['data'] ?? []
        ]);
    }
}
