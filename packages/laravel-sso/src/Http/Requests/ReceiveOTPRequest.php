<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class ReceiveOTPRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'phone' => ['required_without:email', 'regex:' . SSOConstant::REGEX_VALIDATE_PHONE],
            'email' => 'email:rfc,filter|required_without:phone'
        ];
    }

}
