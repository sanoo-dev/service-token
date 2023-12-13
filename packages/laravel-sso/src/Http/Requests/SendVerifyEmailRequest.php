<?php

namespace TuoiTre\SSO\Http\Requests;

class SendVerifyEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'email:rfc,filter|required'
        ];
    }
}
