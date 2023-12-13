<?php

namespace TuoiTre\SSO\Http\Requests;

class RegisterByEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'email' => 'email:rfc,filter|required',
            'password' => 'required|min:6',
        ];
    }
}
