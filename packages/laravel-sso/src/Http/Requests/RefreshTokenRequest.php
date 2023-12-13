<?php

namespace TuoiTre\SSO\Http\Requests;

class RefreshTokenRequest extends ApiRequest
{
    public function rules()
    {
        return [
//            'broker' => 'required|string',
//            'publicKey' => 'required|string',
//            'oldToken' => 'required|string'
        ];
    }
}
