<?php

namespace TuoiTre\SSO\Constants;

final class SSOConstant
{
    public const REGEX_VALIDATE_PHONE = '/((((\+|)84)|0)(3|5|7|8|9)+([0-9]{8}))\b/';

    public const TYPE_OTP_TO_PHONE = 'phone';
    public const TYPE_OTP_TO_EMAIL = 'email';

    public const MEMBER_STATUS_NO_ACTIVE = 1;
    public const MEMBER_STATUS_ACTIVE = 3;

}
