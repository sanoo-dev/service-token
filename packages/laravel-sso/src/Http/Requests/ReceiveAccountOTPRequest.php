<?php

namespace TuoiTre\SSO\Http\Requests;

use TuoiTre\SSO\Constants\SSOConstant;

class ReceiveAccountOTPRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'type' => 'required|in:' . SSOConstant::TYPE_OTP_TO_PHONE . ',' . SSOConstant::TYPE_OTP_TO_EMAIL
        ];
    }
}
