<?php

namespace TuoiTre\SSO\Services;

use App\Modules\Payment\Constants\PaymentConstant;
use Illuminate\Support\Str;
use TuoiTre\SSO\Services\Interfaces\MemberService as MemberServiceInterface;
use TuoiTre\SSO\Traits\CallApiTrait;
use TuoiTre\SSO\Traits\TrackingTrait;

class MemberService implements MemberServiceInterface
{
    use CallApiTrait;
    use TrackingTrait;

    public function __construct(
        protected ?string $memberServerUrl = null
    ) {
        if ($this->memberServerUrl === null) {
            $this->memberServerUrl = config('laravel-sso.memberServerUrl');
        }
    }

    protected function getUrl(string $action): string
    {
        return rtrim($this->memberServerUrl, '/') . '/' . $action;
    }

    private function callWithTrackHeader(string $method, string $url, array $parameters = [], $headers = [])
    {
        $headers = array_merge($headers, [
            'X-Client-Track-Content' => @base64_encode(json_encode($this->getTrackingData()))
        ]);
        return $this->call($method, $url, $parameters, $headers);
    }

    public function info($id): ?array
    {
        $result = $this->callWithTrackHeader('GET', $this->getUrl('member/info'), ['id' => $id]);

        if ($result['success']) {
            return $result['data'] ?? null;
        } else {
            return null;
        }
    }

    public function validate(string $username, string $password, ?array $options = []): array
    {
        $data = [
            'password' => $password,
            'options' => $options
        ];

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $username;
        } else {
            $data['phone'] = $username;
        }

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/login'), $data);
        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function validateOTP(string $phone, string $otp, ?array $options = []): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/loginbyotp'), [
            'phone' => $phone,
            'otp' => $otp,
            'options' => $options
        ]);
        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function receiveOTP(string $emailOrPhone): array
    {
        if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {
            $params = [
                'email' => $emailOrPhone
            ];
        } else {
            $params = [
                'phone' => $emailOrPhone
            ];
        }

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/sendotp'), $params);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null,
            ];
        }
    }

    public function register(array $data): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/register'), [
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'],
            'options' => $data['options'] ?? null
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function registerByPhone(string $phone, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/register-phone'), [
            'phone' => $phone,
            'password' => $password,
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function verifyPhoneOTP(string $phone, string $otp): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/verify-accountphone'), [
            'phone' => $phone,
            'otp' => $otp,
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function registerByEmail(string $email, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/register'), [
            'email' => $email,
            'password' => $password,
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function verifyAccountEmail(string $token): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/verify-account'), [
            'token' => $token,
            'type' => 'verify_account',
        ]);

        if (isset($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function sendVerifyEmail(string $email): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/send-verify'), [
            'email' => $email,
            'type' => 'verify_account',
        ]);

        if (!empty($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function forgotPasswordByPhone(string $phone): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/forgot-password'), [
            'phone' => $phone,
            'type' => 'password_token',
        ]);

        if (!empty($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function changePasswordPhone(string $phone, string $otp, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/changepassword-phone'), [
            'phone' => $phone,
            'otp' => $otp,
            'password' => $password,
            'type' => 'password_token',
        ]);

        if (!empty($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function forgotPasswordByEmail(string $email): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/forgot-password'), [
            'email' => $email,
            'type' => 'password_token',
        ]);

        if (!empty($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function checkTokenPassword(string $token): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/check-token'), [
            'token' => $token,
            'type' => 'password_token',
        ]);
        if (isset($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function changePasswordToken(string $memberId, string $token, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/changepassword-token'), [
            'member_id' => $memberId,
            'token' => $token,
            'password' => $password,
            'type' => 'password_token',
        ]);

        if (!empty($result['error'])) {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        } else {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function updatePhone(string $id, string $phone, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-phone'), [
            'id' => $id,
            'type' => 'update_phone',
            'phone' => $phone,
            'password' => $password,
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function confirmUpdatePhone(string $id, string $phone, string $otp): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/confirm-update-phone'), [
            'id' => $id,
            'phone' => $phone,
            'otp' => $otp,
            'type' => 'update_phone'
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function updateEmail(string $id, string $email, string $password): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-email'), [
            'id' => $id,
            'email' => $email,
            'password' => $password,
            'type' => 'update_email',
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null
            ];
        }
    }

    public function confirmUpdateEmail(string $id, string $email, string $otp): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/confirm-update-email'), [
            'id' => $id,
            'email' => $email,
            'otp' => $otp,
            'type' => 'update_email'
        ]);
        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function resetPassword(string $emailOrPhone): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/reset-password'), [
            'emailOrPhone' => $emailOrPhone
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function updatePassword(string $id, string $password, string $newPassword): array
    {
        $data = [
            'id' => $id,
            'password' => $password,
            'newPassword' => $newPassword,
        ];

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-password'), $data);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function updateName(string $id, string $name, string $password): array
    {
        $data = [
            'id' => $id,
            'name' => $name,
            'password' => $password,
            'type' => 'update_name'
        ];

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-name'), $data);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function updateExtraInfo(string $id, array $data): array
    {
        $data = array_filter([
            'id' => $id,
            'type' => 'update_extra_info',
            'stage' => $data['stage'] ?? null,
            'birth_timestamp' => $data['birthday'] ?? $data['birth_timestamp'] ?? null,
            'gender' => $data['gender'] ?? null
        ], function ($v) {
            return !is_null($v);
        });

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-extra-info'), $data);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function validateAccountOTP(string $id, string $otp, string $type): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/confirm-otp'), [
            'id' => $id,
            'otp' => $otp,
            'type' => $type
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function receiveAccountOTP(string $id, string $type): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/account-otp'), [
            'id' => $id,
            'type' => $type
        ]);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function registerSocial(array $data): array
    {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/register-social'), $data);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null,
            ];
        }
    }

    public function updateAccount(string $id, array $data): array
    {
        $params = array_filter([
            'id' => $id,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'otp' => $data['otp'] ?? null,
            'token' => $data['tokenForUpdate'] ?? null
        ], function ($v) {
            return !is_null($v);
        });

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-account'), $params);

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function updateProfile(string $id, array $data): array
    {
        $params = array_filter([
            'id' => $id,
            'name' => $data['name'] ?? null,
            'stage' => $data['stage'] ?? null,
            'district' => $data['district'] ?? null,
            'ward' => $data['ward'] ?? null,
            'address' => $data['address'] ?? null,
            'birth_timestamp' => $data['birthday'] ?? null,
            'website' => $data['website'] ?? null,
            'gender' => $data['gender'] ?? null,
            'type_account' => $data['typeAccount'] ?? null,
            'company_name' => $data['companyName'] ?? null,
            'company_certificate' => $data['companyCertificate'] ?? null,
            'avatarBaseUrl' => $data['avatarBaseUrl'] ?? null,
            'avatar_path' => $data['avatarPath'] ?? null
        ], function ($v) {
            return !is_null($v);
        });

        $result = $this->callWithTrackHeader('POST', $this->getUrl('member/update-profile'), $params);


        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
    }

    public function donate($data): array
    {
        $params = array_filter([
            'member_id' => $data['member_id'],
            'star' => $data['number_of_stars'],
            'app_id' => (int)env('DEFAULT_APP_ID', 25),
            'payment_type' => PaymentConstant::PAYMENT_TYPE_DONATE,
            'article_link' => $data['article_link'],
        ], function ($v) {
            return !is_null($v);
        });
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member-point/donate'), $params);
        return $result;
    }

    public function buyTicket(string $id, string $codeEvent, string $name, string $email, string $phone): ?array
    {
        $result = $this->callWithTrackHeader(
            'POST', $this->getUrl('member-ticket/buy-ticket'),
            [
                'id' => $id,
                'code_event' => $codeEvent,
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ]
        );

        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null
            ];
        }
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
    ): ?array {
        $result = $this->callWithTrackHeader(
            'POST', $this->getUrl('member-point/transfer-star'),
            [
                'payment_type' => $paymentType,
                'member_id' => $memberId,
                'app_id' => $appId,
                'star' => $star,
                'type_info' => $typeInfo,
                'phone_or_email' => $phoneOrEmail,
                'to_member_id' => $toMemberId,
                'article_link' => $articleLink,
                'comment_content' => $commentContent,
                'name' => $name,
                'type' => $type,
                'otp' => $otp
            ]
        );
        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'errors' => $result['error?'] ?? $result['error'] ?? null
            ];
        }
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
    ): ?array {
        $result = $this->callWithTrackHeader('POST', $this->getUrl('member-point/otp-transfer'), [
            'member_id' => $memberId,
            'type' => $type,
            'type_info' => $typeInfo,
            'phone_or_email' => $phoneOrEmail,
            'to_member_id' => $toMemberId,
            'name' => $name,
            'star' => $star,
            'article_link' => $articleLink,
            'comment_content' => $commentContent
        ]);
        if ($result['success']) {
            return [
                'success' => true,
                'data' => $result['data'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'error' => $result['error'] ?? null,
                'data' => $result['data'] ?? null
            ];
        }
    }

}
