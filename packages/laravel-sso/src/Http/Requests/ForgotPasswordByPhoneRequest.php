<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class ForgotPasswordByPhoneRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'phone' => ['required', 'regex:' . SSOConstant::REGEX_VALIDATE_PHONE]
        ];
    }
}
