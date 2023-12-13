<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class UpdatePhoneRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'password' => 'required',
            'phone' => ['required', 'regex:' . SSOConstant::REGEX_VALIDATE_PHONE]
        ];
    }
}
