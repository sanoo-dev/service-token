<?php

namespace TuoiTre\SSO\Http\Requests;

class LoginByPasswordRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
    }

}
