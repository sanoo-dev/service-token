<?php

namespace TuoiTre\SSO\Services;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use TuoiTre\SSO\Constants\ResponseTypeConstant;
use TuoiTre\SSO\Constants\SSOConstant;
use TuoiTre\SSO\Services\Interfaces\BrokerService;
use TuoiTre\SSO\Services\Interfaces\MemberService;
use TuoiTre\SSO\Services\Interfaces\CacheService;
use TuoiTre\SSO\Services\Interfaces\SSOServerService as SSOServerServiceInterface;
use TuoiTre\SSO\Traits\BrokerSessionTrait;
use TuoiTre\SSO\Traits\JwtTrait;
use TuoiTre\SSO\Traits\ReturnTrait;
use TuoiTre\SSO\Traits\SessionTrait;
use TuoiTre\SSO\Traits\TrackingTrait;

class SSOServerService implements SSOServerServiceInterface
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

    public function token(string $broker, string $publicKey, array $options = []): array
    {
        if (($brokerInfo = $this->brokerService->validate($broker, $publicKey)) === false) {
            return $this->returnArrError([
                'message' => __('sso::messages.broker.notExist'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_BROKER_INVALID);
        }
        $token = $this->generateBrokerToken();
        $this->setBrokerSessionData(
            $token,
            $this->generateBrokerSessionData(
                null,
                $options['expiredMinutes'] ?? null
            )
        );
        $jwt = $this->encodeJwt(
            ['token' => $token, 'broker' => $brokerInfo['name']],
            $brokerInfo['secret_key']
        );
        return $this->returnArrSuccess([
            'token' => $jwt,
            'jwt' => $jwt,
        ]);
    }

    public function refreshToken(): array
    {
        $oldJwt = $this->getJwtFromHeader();

        $payloadNotValidate = $this->getPayloadFromJwtNotValidate($oldJwt);
        if (empty($payloadNotValidate['broker'])) {
            return $this->returnArrError([
                'message' => __('sso::messages.broker.notExist'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_BROKER_INVALID);
        }

        if (($brokerInfo = $this->brokerService->info($payloadNotValidate['broker'])) === false) {
            return $this->returnArrError([
                'message' => __('sso::messages.broker.cannotAccess'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_BROKER_INVALID);
        }

        $payload = $this->getPayloadFromJwt($oldJwt, $brokerInfo['secret_key']);

        if (empty($payload['token'])) {
            return $this->returnArrError([
                'message' => __('sso::messages.token.oldTokenInvalid'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST
            ], ResponseTypeConstant::TYPE_ERROR_TOKEN_INVALID);
        }

        $oldBrokerSession = $this->getBrokerSessionData($payload['token']);
        if (empty($oldBrokerSession)) {
            return $this->returnArrError([
                'message' => __('sso::messages.token.refresh.expired'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'require_new' => true
            ], ResponseTypeConstant::TYPE_ERROR_TOKEN_REFRESH_EXPIRED);
        }
        $newToken = $this->generateBrokerToken();
        $this->setBrokerSessionData(
            $newToken,
            $this->generateBrokerSessionData(
                $oldBrokerSession['memberId'] ?? null,
                $oldBrokerSession['expiredAfterMinutes'] ?? null
            ),
        );
        $this->deleteBrokerSessionData($payload['token']);
        return $this->returnArrSuccess([
            'token' => $newToken,
            'jwt' => $this->encodeJwt(
                ['token' => $newToken, 'broker' => $brokerInfo['name']],
                $brokerInfo['secret_key']
            )
        ]);
    }

    public function login(string $username, string $password, bool $remember = true, ?array $options = []): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->validate($username, $password, $options);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.loginFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'username' => $result['error'] ?? __('sso::messages.auth.loginFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_VERIFIED);
        }
        $member = $result['data'] ?? null;
        $this->cacheService->set("info_" . ($member['id'] ?? '-' . Str::random(4)), json_encode($member), 3);
        $arrToken = $this->startNewBrokerSession($member, $remember);
        return $this->returnArrSuccess([
            'data' => [
                'token' => $arrToken['token'],
                'jwt' => $arrToken['jwt'],
                'info' => $member
            ]
        ]);
    }

    public function otp(string $phone, string $otp, bool $remember = true, ?array $options = []): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->validateOTP($phone, $otp, $options);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.loginFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'otp' => $result['error'] ?? __('sso::messages.auth.otp.loginFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_VERIFIED);
        }
        $member = $result['data'] ?? null;
        $this->cacheService->set("info_" . ($member['id'] ?? '-' . Str::random(4)), json_encode($member), 3);
        $arrToken = $this->startNewBrokerSession($member, $remember);
        return $this->returnArrSuccess([
            'data' => [
                'token' => $arrToken['token'],
                'jwt' => $arrToken['jwt'],
                'info' => $member
            ]
        ]);
    }

    public function socialLogin(array $data, bool $remember = true, ?array $options = []): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->registerSocial($data);
        if (empty($result['data'])) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'otp' => $result['error'] ?? __('sso::messages.otherError')
                ]
            ]);
        }
        $member = $result['data'];
        $this->cacheService->set("info_" . ($member['id'] ?? '-' . Str::random(4)), json_encode($member), 3);
        $arrToken = $this->startNewBrokerSession($member, $remember);
        return $this->returnArrSuccess([
            'data' => [
                'token' => $arrToken['token'],
                'jwt' => $arrToken['jwt'],
                'info' => $member
            ]
        ]);
    }

    public function receiveOTP(string $emailOrPhone): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->receiveOTP($emailOrPhone);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed')
                ],
                'data' => [
                    'timeout' => $result['data']['timeout'] ?? null,
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
        }
        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function register(array $data): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->register($data);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.registerFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
        }
        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function registerByPhone(string $phone, string $password): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->registerByPhone($phone, $password);


        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed'),
                    'timeout' => $result['data']['timeout'] ?? 0
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
        }
        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function verifyPhoneOTP(string $phone, string $otp, bool $remember = true): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->verifyPhoneOTP($phone, $otp);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.registerFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_VERIFIED);
        }
        $member = $result['data'] ?? null;
        $this->cacheService->set("info_" . ($member['id'] ?? '-' . Str::random(4)), json_encode($member), 3);
        $arrToken = $this->startNewBrokerSession($member, $remember);
        return $this->returnArrSuccess([
            'data' => [
                'token' => $arrToken['token'] ?? null,
                'jwt' => $arrToken['jwt'] ?? null,
                'info' => $member
            ]
        ]);
    }


    public function registerByEmail(string $email, string $password, bool $remember = true): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->registerByEmail($email, $password);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.registerFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
        }

        $member = $result['data'] ?? null;
        $this->cacheService->set("info_" . ($member['id'] ?? '-' . Str::random(4)), json_encode($member), 3);
        $arrToken = $this->startNewBrokerSession($member, $remember);
        return $this->returnArrSuccess([
            'data' => [
                'token' => $arrToken['token'] ?? null,
                'jwt' => $arrToken['jwt'] ?? null,
                'info' => $member
            ]
        ]);
    }

    public function verifyAccountEmail(string $token): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->verifyAccountEmail($token);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.verifyAccountFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_OTHER);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function sendVerifyEmail(string $email): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->sendVerifyEmail($email);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.getLinkFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_OTHER);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function forgotPasswordByPhone(string $phone): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->forgotPasswordByPhone($phone);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed'),
                    'timeout' => $result['data']['timeout'] ?? null
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function changePasswordPhone(string $phone, string $otp, string $password): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->changePasswordPhone($phone, $otp, $password);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.update.passwordFailed'),
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function forgotPasswordByEmail(string $email): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->forgotPasswordByEmail($email);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.getLinkFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_OTHER);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function checkTokenPassword(string $token): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->checkTokenPassword($token);

        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.verifyAccountFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_OTHER);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? null
        ]);
    }

    public function changePasswordToken(string $token, string $password): array
    {
        $this->preparedToCallMember();
        $data = $this->memberService->checkTokenPassword($token);
        if (!empty($data['data']['member_id'])) {
            $result = $this->memberService->changePasswordToken($data['data']['member_id'], $token, $password);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.resetPasswordFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.resetPasswordFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountFailed'),
            'errorStatusCode' => Response::HTTP_BAD_REQUEST,
            'errors' => [
                'other' => $result['error'] ?? __('sso::messages.auth.verifyAccountFailed')
            ]
        ], ResponseTypeConstant::TYPE_ERROR_OTHER);
    }

    public function updatePhone(string $phone, string $password): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updatePhone($memberId, $phone, $password);

            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed'),
                        'timeout' => $result['data']['timeout'] ?? null
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function confirmUpdatePhone(string $phone, string $otp): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->confirmUpdatePhone($memberId, $phone, $otp);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.otherError'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.otherError')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updateEmail(string $email, string $password): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $member = $this->memberService->info($memberId);
            /**
             * Check member is active and old email like new email
             */
            if (!empty($member['email'])
                && $member['email'] === $email
                && !empty($member['status'])
                && $member['status'] !== SSOConstant::MEMBER_STATUS_NO_ACTIVE
            ) {
                return $this->returnArrSuccess([
                    'data' => null
                ]);
            }
            $result = $this->memberService->updateEmail($memberId, $email, $password);

            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['error'] ?? __('sso::messages.auth.getLinkFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.getLinkFailed'),
                        'timeout' => $result['data']['timeout'] ?? null
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function confirmUpdateEmail(string $email, string $otp): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->confirmUpdateEmail($memberId, $email, $otp);

            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.otherError'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.otherError')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updateName(string $name, string $password): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updateName($memberId, $name, $password);

            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.otherError'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.otherError')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updateExtraInfo(array $data): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updateExtraInfo($memberId, $data);

            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.otherError'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.otherError')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_OTHER);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? null
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }


    public function logout(): array
    {
        $arrToken = $this->startNewBrokerSession();
        return $this->returnArrSuccess([
            'token' => $arrToken['token'],
            'jwt' => $arrToken['jwt'],
        ]);
    }

    public function info(bool $ignoreCache = false): array
    {
        $memberId = $this->getMemberIdFromBrokerSession();
        if (!$memberId) {
            return $this->returnArrError([
                'message' => __('sso::messages.auth.notLogin'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
        }

        if (!empty($jsonMember = $this->cacheService->get("info_$memberId"))) {
            $member = @json_decode($jsonMember, true);
        } else {
            $this->preparedToCallMember();
            $member = $this->memberService->info($memberId);
            if (!empty($member) && !$ignoreCache) {
                $this->cacheService->set("info_$memberId", json_encode($member));
            }
        }

        if (empty($member)) {
            $payload = $this->getPayloadFromJwtNotValidate($this->getJwtFromHeader());
            $this->setBrokerSessionData(
                $payload['token'],
                $this->generateBrokerSessionData()
            );
            return $this->returnArrError([
                'message' => __('sso::messages.auth.notFound'),
                'errorStatusCode' => Response::HTTP_NOT_FOUND
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_FOUND);
        }

        return $this->returnArrSuccess([
            'data' => $member
        ]);
    }

    public function isLogin(): mixed
    {
        if (!empty($memberId = $this->getMemberIdFromBrokerSession())) {
            return $memberId;
        }
        return false;
    }

    public function resetPassword(string $emailOrPhone): array
    {
        $this->preparedToCallMember();
        $result = $this->memberService->resetPassword($emailOrPhone);
        if (!isset($result['success']) || !$result['success']) {
            return $this->returnArrError([
                'message' => $result['message'] ?? __('sso::messages.auth.resetPasswordFailed'),
                'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    'other' => $result['error'] ?? __('sso::messages.auth.resetPasswordFailed')
                ]
            ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_RESET_PASSWORD);
        }

        return $this->returnArrSuccess([
            'data' => $result['data'] ?? []
        ]);
    }

    public function verifyAccountOTP(string $otp, string $type): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->validateAccountOTP($memberId, $otp, $type);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.otp.verifyFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.otp.verifyFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
            }
            return $this->returnArrSuccess([
                'data' => [
                    'tokenForUpdate' => $result['data']['token'] ?? null
                ]
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function receiveAccountOTP(string $type): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->receiveAccountOTP($memberId, $type);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.otp.sendFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
            }
            return $this->returnArrSuccess([
                'data' => $result['data'] ?? []
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updateAccount(array $data): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updateAccount($memberId, $data);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.update.accountFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.update.accountFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
            }
            return $this->returnArrSuccess([
                'data' => $result['data'] ?? []
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updatePassword(string $password, string $newPassword): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updatePassword($memberId, $password, $newPassword);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.update.passwordFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
            }

            return $this->returnArrSuccess([
                'data' => $result['data'] ?? []
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function updateProfile(array $data): array
    {
        if ($memberId = $this->isLogin()) {
            $this->preparedToCallMember();
            $result = $this->memberService->updateProfile($memberId, $data);
            if (!isset($result['success']) || !$result['success']) {
                return $this->returnArrError([
                    'message' => $result['message'] ?? __('sso::messages.auth.update.profileFailed'),
                    'errorStatusCode' => Response::HTTP_BAD_REQUEST,
                    'errors' => [
                        'other' => $result['error'] ?? __('sso::messages.auth.update.profileFailed')
                    ]
                ], ResponseTypeConstant::TYPE_ERROR_MEMBER_DATA_INVALID);
            }
            return $this->returnArrSuccess([
                'data' => $result['data'] ?? []
            ]);
        }
        return $this->returnArrError([
            'message' => __('sso::messages.auth.notLogin'),
            'errorStatusCode' => Response::HTTP_UNAUTHORIZED
        ], ResponseTypeConstant::TYPE_ERROR_MEMBER_NOT_LOGIN);
    }

    public function startBrokerSession(): bool|array
    {
        $jwt = $this->getJwtFromHeader();
        if (empty($jwt)) {
            return $this->returnArrError([
                'message' => __('sso::messages.authorizationInvalid'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_AUTHORIZATION_INVALID);
        }

        $payloadNotValidate = $this->getPayloadFromJwtNotValidate($jwt);
        $broker = $this->brokerService->info($payloadNotValidate['broker'] ?? null);
        if (empty($broker)) {
            return $this->returnArrError([
                'message' => __('sso::messages.broker.notExist'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_BROKER_INVALID);
        }

        $payload = $this->getPayloadFromJwt($jwt, $broker['secret_key']);
        if (empty($payload['token'])) {
            return $this->returnArrError([
                'message' => __('sso::messages.token.invalid'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED
            ], ResponseTypeConstant::TYPE_ERROR_TOKEN_INVALID);
        }

        $sessionData = $this->getBrokerSessionData($payload['token']);
        if (empty($sessionData)) {
            return $this->returnArrError([
                'message' => __('sso::messages.token.refresh.expired'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED,
                'require_new' => true,
                'requireNew' => true
            ], ResponseTypeConstant::TYPE_ERROR_TOKEN_REFRESH_EXPIRED);
        }

        if ($sessionData['startAt'] + $sessionData['expiredAfterMinutes'] * 60 <= time()) {
            return $this->returnArrError([
                'message' => __('sso::messages.token.expired'),
                'errorStatusCode' => Response::HTTP_UNAUTHORIZED,
                'require_refresh' => true,
                'requireRefresh' => true
            ], ResponseTypeConstant::TYPE_ERROR_TOKEN_EXPIRED);
        }
        return true;
    }

    protected function startNewBrokerSession(?array $member = null, bool $rememberMember = true): array
    {
        $token = $this->generateBrokerToken();
        $memberId = $member['id'] ?? null;

        $this->setBrokerSessionData(
            $token,
            $this->generateBrokerSessionData(
                $memberId,
                !empty($memberId) && $rememberMember ? config('laravel-sso.defaultRememberLoginExpiredMinutes') : null
            )
        );
        $jwt = $this->getJwtFromHeader();
        if (!empty($jwt)) {
            $payload = $this->getPayloadFromJwtNotValidate($jwt);
            $this->deleteBrokerSessionData($payload['token']);
            $payloadNotValidate = $this->getPayloadFromJwtNotValidate($jwt);
            $brokerInfo = $this->brokerService->info($payloadNotValidate['broker'] ?? null);
            if (!empty($brokerInfo)) {
                $newJwt = $this->encodeJwt(
                    ['token' => $token, 'broker' => $brokerInfo['name']],
                    $brokerInfo['secret_key']
                );
            }
        }

        return [
            'token' => $token,
            'jwt' => $newJwt ?? null
        ];
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

    protected function generateBrokerToken(): string
    {
        return hash("sha256", "token" . Str::random(64) . time());
    }
}
