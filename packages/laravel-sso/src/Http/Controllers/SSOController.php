<?php

namespace TuoiTre\SSO\Http\Controllers;

use TuoiTre\SSO\Http\Requests\VerifyAccountEmailRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Log;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\Response;
use TuoiTre\SSO\Constants\ResponseTypeConstant;
use TuoiTre\SSO\Http\Requests\ChangePasswordPhoneRequest;
use TuoiTre\SSO\Http\Requests\ChangePasswordTokenRequest;
use TuoiTre\SSO\Http\Requests\CheckTokenPasswordRequest;
use TuoiTre\SSO\Http\Requests\ConfirmUpdateEmailRequest;
use TuoiTre\SSO\Http\Requests\ConfirmUpdatePhoneRequest;
use TuoiTre\SSO\Http\Requests\BuyTicketRequest;
use TuoiTre\SSO\Http\Requests\DonateRequest;
use TuoiTre\SSO\Http\Requests\ForgotPasswordByEmailRequest;
use TuoiTre\SSO\Http\Requests\ForgotPasswordByPhoneRequest;
use TuoiTre\SSO\Http\Requests\ReceiveAccountOTPRequest;
use TuoiTre\SSO\Http\Requests\RegisterByEmailRequest;
use TuoiTre\SSO\Http\Requests\RegisterByPhoneRequest;
use TuoiTre\SSO\Http\Requests\LoginByPasswordRequest;
use TuoiTre\SSO\Http\Requests\OTPTransferRequest;
use TuoiTre\SSO\Http\Requests\ResetPasswordRequest;
use TuoiTre\SSO\Http\Requests\OTPRequest;
use TuoiTre\SSO\Http\Requests\ReceiveOTPRequest;
use TuoiTre\SSO\Http\Requests\RefreshTokenRequest;
use TuoiTre\SSO\Http\Requests\RegisterRequest;
use TuoiTre\SSO\Http\Requests\SendVerifyEmailRequest;
use TuoiTre\SSO\Http\Requests\TokenRequest;
use TuoiTre\SSO\Http\Requests\UpdateEmailRequest;
use TuoiTre\SSO\Http\Requests\UpdateExtraInfoRequest;
use TuoiTre\SSO\Http\Requests\UpdateNameRequest;
use TuoiTre\SSO\Http\Requests\UpdatePasswordV2Request;
use TuoiTre\SSO\Http\Requests\UpdatePhoneRequest;
use TuoiTre\SSO\Http\Requests\TransferStarRequest;
use TuoiTre\SSO\Http\Requests\UpdateProfileRequest;
use TuoiTre\SSO\Http\Requests\UpdateAccountRequest;
use TuoiTre\SSO\Http\Requests\UpdatePasswordRequest;
use TuoiTre\SSO\Http\Requests\VerifyAccountOTPRequest;
use TuoiTre\SSO\Http\Requests\VerifyPhoneOTPRequest;
use TuoiTre\SSO\Services\DonateService;
use TuoiTre\SSO\Services\EventService;
use TuoiTre\SSO\Services\Interfaces\SSOServerService;
use TuoiTre\SSO\Traits\ClientInfoTrait;
use TuoiTre\SSO\Traits\ResponseApiTrait;

class SSOController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ResponseApiTrait;
    use ValidatesRequests;
    use ClientInfoTrait;

    public function __construct(
        protected Request $request,
        protected SSOServerService $SSOServerService,
        protected DonateService $donateService,
        protected EventService $eventService,
    ) {
        $this->SSOServerService->setTrackingData($this->getClientInfoFromRequest($this->request));
    }

    public function token(TokenRequest $request): JsonResponse
    {
        $brokerName = $request->get('broker');
        $publicKey = $request->get('publicKey');
        $result = $this->SSOServerService->token($brokerName, $publicKey);

        if (!empty($result['token'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.token.getSuccess'),
                'token' => $result['token'],
                'jwt' => $result['jwt'] ?? null,
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.token.getFailed'),
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->refreshToken();

        if (!empty($result['token'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.token.refresh.getSuccess'),
                'token' => $result['token'],
                'jwt' => $result['jwt'] ?? null
            ]);
        } else {
            $errorStatusCode = $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST;
            unset($result['errorStatusCode']);
            return $this->responseError($result, $errorStatusCode);
        }
    }

    public function receiveOTP(ReceiveOTPRequest $request): JsonResponse
    {
        Log::error("SSO - Receive otp with phone or email: " . ($request->post('phone') ?? $request->post('email')));
        $result = $this->SSOServerService->receiveOTP(
            (string)($request->post('phone') ?? $request->post('email'))
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errors' => $result['errors'] ?? [],
                'data' => $result['data'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function otp(OTPRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->otp(
            $request->post('phone'),
            $request->post('otp'),
            $request->boolean('remember'),
            $request->post('options', [])
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.loginSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.loginFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function login(LoginByPasswordRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->login(
            $request->post('username'),
            $request->post('password'),
            $request->boolean('remember'),
            $request->post('options', [])
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.loginSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.loginFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->register($request->post());
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.registerSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function registerByPhone(RegisterByPhoneRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->registerByPhone($request->post('phone'), $request->post('password'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function verifyPhoneOTP(VerifyPhoneOTPRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->verifyPhoneOTP($request->post('phone'), $request->post('otp'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.registerSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function registerByEmail(RegisterByEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->registerByEmail($request->post('email'), $request->post('password'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.registerSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.registerFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function sendVerifyEmail(SendVerifyEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->sendVerifyEmail($request->post('email'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function verifyAccountEmail(VerifyAccountEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->verifyAccountEmail($request->post('token'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function forgotPasswordByPhone(ForgotPasswordByPhoneRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->forgotPasswordByPhone($request->post('phone'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_MEMBER_SEND_OTP_FAILED
            ], $result['errorStatusCode']);
        }
    }

    public function changePasswordPhone(ChangePasswordPhoneRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->changePasswordPhone(
            $request->post('phone'),
            $request->post('otp'),
            $request->post('password')
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function forgotPasswordByEmail(ForgotPasswordByEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->forgotPasswordByEmail($request->post('email'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function checkTokenPassword(CheckTokenPasswordRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->checkTokenPassword($request->post('token'));
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.verifyAccountFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function changePasswordToken(ChangePasswordTokenRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->changePasswordToken($request->post('token'), $request->post('password'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updatePhone(UpdatePhoneRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updatePhone($request->post('phone'), $request->post('password'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.success'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function confirmUpdatePhone(ConfirmUpdatePhoneRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->confirmUpdatePhone($request->post('phone'), $request->post('otp'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.success'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updateEmail($request->post('email'), $request->post('password'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.getLinkFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function confirmUpdateEmail(ConfirmUpdateEmailRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->confirmUpdateEmail($request->post('email'), $request->post('otp'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.success'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updateName(UpdateNameRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updateName($request->post('name'), $request->post('password'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.success'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updateExtraInfo(UpdateExtraInfoRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updateExtraInfo($request->post());

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.success'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.otherError'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function logout(): JsonResponse
    {
        $result = $this->SSOServerService->logout();
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.logoutSuccess'),
                'token' => $result['token'] ?? null,
                'jwt' => $result['jwt'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.logoutFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ]);
        }
    }

    public function info(): JsonResponse
    {
        $result = $this->SSOServerService->info();
        if (!isset($result['errorStatusCode']) && !empty($result['data'])) {
            return $this->responseSuccess([
                'message' => __('sso::messages.auth.getInfoSuccess'),
                'data' => $result['data']
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.getInfoFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function isLogin(): JsonResponse
    {
        $result = $this->SSOServerService->isLogin();
        if ($result === false) {
            return $this->responseSuccess([
                'login' => false
            ]);
        } else {
            return $this->responseSuccess([
                'login' => true,
                'data' => [
                    'id' => $result
                ]
            ]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->resetPassword($request->post('emailOrPhone'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.resetPasswordSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.resetPasswordFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updatePassword(
            $request->post('oldPassword'),
            $request->post('newPassword')
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updatePasswordV2(UpdatePasswordV2Request $request): JsonResponse
    {
        $result = $this->SSOServerService->updatePassword(
            $request->post('oldPassword'),
            $request->post('newPassword')
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.passwordFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updateProfile($request->post());

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.profileSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.profileFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function updateAccount(UpdateAccountRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->updateAccount($request->post());

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.update.accountSuccess'),
                'data' => $result['data'] ?? []
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.update.accountFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode']);
        }
    }

    public function receiveAccountOTP(ReceiveAccountOTPRequest $request): JsonResponse
    {
        Log::error("SSO - Receive account otp with type " . $request->post('type'));
        $result = $this->SSOServerService->receiveAccountOTP($request->post('type'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function donate(DonateRequest $request): JsonResponse
    {
        $result = $this->donateService->donate($request->get('numberOfStars'), $request->get('articleLink'));
        if (isset($result['data'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.donate.donateSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.donate.donateFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function buyTicket(BuyTicketRequest $request): JsonResponse
    {
        $result = $this->eventService->buyTicket(
            $request->post('id'),
            $request->post('code_event'),
            $request->post('name'),
            $request->post('email'),
            $request->post('phone')
        );
        if (isset($result['data'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.event.buyTicketSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.event.buyTicketFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }


    public function verifyAccountOTP(VerifyAccountOTPRequest $request): JsonResponse
    {
        $result = $this->SSOServerService->verifyAccountOTP($request->post('otp'), $request->post('type'));

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.verifySuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.verifyFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function transferStar(TransferStarRequest $request): JsonResponse
    {
        $result = $this->donateService->transferStar(
            $request->post('payment_type'),
            $request->post('member_id'),
            $request->post('app_id'),
            $request->post('star'),
            $request->post('type_info'),
            $request->post('phone_or_email'),
            $request->post('to_member_id'),
            $request->post('article_link'),
            $request->post('comment_content'),
            $request->post('name'),
            $request->post('type'),
            $request->post('otp')
        );

        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.transfer.transferSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.transfer.transferFailed'),
                'errors' => $result['errors'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }

    public function OTPTransfer(OTPTransferRequest $request): JsonResponse
    {
        $result = $this->donateService->OTPTransfer(
            $request->post('member_id'),
            $request->post('type'),
            $request->post('type_info'),
            $request->post('phone_or_email'),
            $request->post('to_member_id'),
            $request->post('name'),
            $request->post('star'),
            $request->post('article_link'),
            $request->post('comment_content'),
        );
        if (!isset($result['errorStatusCode'])) {
            return $this->responseSuccess([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendSuccess'),
                'data' => $result['data'] ?? null
            ]);
        } else {
            return $this->responseError([
                'message' => $result['message'] ?? __('sso::messages.auth.otp.sendFailed'),
                'errors' => $result['errors'] ?? [],
                'data' => $result['data'] ?? [],
                'type' => $result['type'] ?? ResponseTypeConstant::TYPE_ERROR_OTHER
            ], $result['errorStatusCode'] ?? Response::HTTP_BAD_REQUEST);
        }
    }
}
