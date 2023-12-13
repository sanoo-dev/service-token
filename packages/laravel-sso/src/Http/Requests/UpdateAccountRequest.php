<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class UpdateAccountRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'required_without:phone|email:rfc,filter',
            'phone' => ['required_without:email', 'regex:' . SSOConstant::REGEX_VALIDATE_PHONE],
            'otp' => 'required|string|digits:6',
            'tokenForUpdate' => 'required|string'
        ];
    }
}
