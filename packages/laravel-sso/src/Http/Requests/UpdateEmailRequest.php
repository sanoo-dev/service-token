<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdateEmailRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'password' => 'required',
            'email' => 'required|email:rfc,filter'
        ];
    }
}
