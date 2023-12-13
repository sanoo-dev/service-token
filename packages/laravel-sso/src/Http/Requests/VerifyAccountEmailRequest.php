<?php

namespace TuoiTre\SSO\Http\Requests;

class VerifyAccountEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'token' => 'required'
        ];
    }
}
