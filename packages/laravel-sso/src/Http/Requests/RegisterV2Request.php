<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class RegisterV2Request extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'email:rfc,filter|required',
//            'email' => 'email:rfc,filter|required_without:phone',
            'phone' => ['required_without:email', 'regex:' . SSOConstant::REGEX_VALIDATE_PHONE],
            'password' => 'required',
//            'rePassword' => 'required|same:password|min:6'
        ];
    }
}
