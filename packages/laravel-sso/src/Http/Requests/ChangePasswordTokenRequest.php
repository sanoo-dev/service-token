<?php

namespace TuoiTre\SSO\Http\Requests;

class ChangePasswordTokenRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'token' => 'required',
            'password' => 'required|min:6'
        ];
    }
}
