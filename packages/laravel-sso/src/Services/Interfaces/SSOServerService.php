<?php

namespace TuoiTre\SSO\Services\Interfaces;

interface SSOServerService
{

    public function token(string $broker, string $publicKey, array $options = []): array;

    public function refreshToken(): array;

    public function login(string $username, string $password, bool $remember = true, ?array $options = []): array;

    public function otp(string $phone, string $otp, bool $remember = true, ?array $options = []): array;

    public function socialLogin(array $data, bool $remember = true, ?array $options = []): array;

    public function receiveOTP(string $emailOrPhone): array;

    public function register(array $data): array;

    public function registerByPhone(string $phone, string $password): array;

    public function verifyPhoneOTP(string $phone, string $otp, bool $remember = true): array;

    public function registerByEmail(string $email, string $password, bool $remember = true): array;

    public function verifyAccountEmail(string $token): array;

    public function sendVerifyEmail(string $email): array;

    public function forgotPasswordByPhone(string $phone): array;

    public function changePasswordPhone(string $phone, string $otp, string $password): array;

    public function forgotPasswordByEmail(string $email): array;

    public function checkTokenPassword(string $token): array;

    public function changePasswordToken(string $token, string $password): array;

    public function updatePhone(string $phone, string $password): array;

    public function confirmUpdatePhone(string $phone, string $otp): array;

    public function updateEmail(string $email, string $password): array;

    public function confirmUpdateEmail(string $email, string $otp): array;

    public function updateName(string $name, string $password): array;

    public function updateExtraInfo(array $data): array;

    public function logout(): array;

    public function resetPassword(string $emailOrPhone): array;

    public function verifyAccountOTP(string $otp, string $type): array;

    public function receiveAccountOTP(string $type): array;

    public function updateAccount(array $data): array;

    public function updatePassword(string $password, string $newPassword): array;

    public function updateProfile(array $data): array;

    public function info(bool $ignoreCache = false): ?array;

    public function isLogin(): mixed;

    public function startBrokerSession(): bool|array;
}
