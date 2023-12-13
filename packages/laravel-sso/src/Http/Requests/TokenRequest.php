<?php

namespace TuoiTre\SSO\Http\Requests;

class TokenRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'broker' => 'required|string',
            'publicKey' => 'required|string'
        ];
    }
}
