<?php

namespace TuoiTre\SSO\Services\Interfaces;

interface MemberService
{
    public function info($id): ?array;

    public function validate(string $username, string $password, ?array $options = []): array;

    public function validateOTP(string $phone, string $otp, ?array $options = []): array;

    public function receiveOTP(string $emailOrPhone): array;

    public function register(array $data): array;

    public function registerByPhone(string $phone, string $password): array;

    public function verifyPhoneOTP(string $phone, string $otp): array;

    public function registerByEmail(string $email, string $password): array;

    public function verifyAccountEmail(string $token): array;

    public function sendVerifyEmail(string $email): array;

    public function forgotPasswordByPhone(string $phone): array;

    public function changePasswordPhone(string $phone, string $otp, string $password): array;

    public function forgotPasswordByEmail(string $email): array;

    public function checkTokenPassword(string $token): array;

    public function changePasswordToken(string $memberId, string $token, string $password): array;

    public function updatePhone(string $id, string $phone, string $password): array;

    public function confirmUpdatePhone(string $id, string $phone, string $otp): array;

    public function updateEmail(string $id, string $email, string $password): array;

    public function confirmUpdateEmail(string $id, string $email, string $otp): array;

    public function updateName(string $id, string $name, string $password): array;

    public function updateExtraInfo(string $id, array $data): array;

    public function resetPassword(string $emailOrPhone): array;

    public function validateAccountOTP(string $id, string $otp, string $type): array;

    public function receiveAccountOTP(string $id, string $type): array;

    public function registerSocial(array $data): array;

    public function updateAccount(string $id, array $data): array;

    public function updatePassword(string $id, string $password, string $newPassword): array;

    public function updateProfile(string $id, array $data): array;

    public function donate(array $data): ?array;

    public function buyTicket(string $id, string $codeEvent, string $name, string $email, string $phone): ?array;

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
    ): ?array;

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
    ): ?array;

}
