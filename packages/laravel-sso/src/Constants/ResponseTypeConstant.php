<?php

namespace TuoiTre\SSO\Constants;

final class ResponseTypeConstant
{
    public const TYPE_SUCCESS = 1;

    public const TYPE_ERROR_AUTHORIZATION_INVALID = 2;
    public const TYPE_ERROR_BROKER_INVALID = 3;
    public const TYPE_ERROR_PARAMS_INVALID = 4;

    public const TYPE_ERROR_TOKEN_INVALID = 10;
    public const TYPE_ERROR_TOKEN_EXPIRED = 11;
    public const TYPE_ERROR_TOKEN_REFRESH_EXPIRED = 12;

    public const TYPE_ERROR_MEMBER_SEND_OTP_FAILED = 20;
    public const TYPE_ERROR_MEMBER_NOT_VERIFIED = 21;
    public const TYPE_ERROR_MEMBER_DATA_INVALID = 22;
    public const TYPE_ERROR_MEMBER_NOT_LOGIN = 23;
    public const TYPE_ERROR_MEMBER_NOT_FOUND = 24;
    public const TYPE_ERROR_MEMBER_NOT_RESET_PASSWORD = 25;
    public const TYPE_ERROR_MEMBER_LOGOUT_FAILED = 26;

    public const TYPE_ERROR_OTHER = 100;
}
