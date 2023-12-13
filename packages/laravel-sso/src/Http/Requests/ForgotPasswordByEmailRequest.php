<?php

namespace TuoiTre\SSO\Http\Requests;

class ForgotPasswordByEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,filter'
        ];
    }
}
