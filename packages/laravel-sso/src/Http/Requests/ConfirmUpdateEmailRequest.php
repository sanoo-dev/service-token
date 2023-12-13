<?php

namespace TuoiTre\SSO\Http\Requests;

class ConfirmUpdateEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,filter',
            'otp' => 'required|string|digits:6'
        ];
    }
}
