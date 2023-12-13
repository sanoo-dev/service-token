<?php

namespace App\Modules\Token\Http\Requests;



use TuoiTre\SSO\Http\Requests\ApiRequest;

class VerifyTokenRequest extends ApiRequest
{
    public function rules() {
        return [
            'publicKey' => 'required|string',
            'jwt' => 'required|string',

        ];
    }
}
