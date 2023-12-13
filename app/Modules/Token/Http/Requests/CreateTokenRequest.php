<?php

namespace App\Modules\Token\Http\Requests;



use TuoiTre\SSO\Http\Requests\ApiRequest;

class CreateTokenRequest extends ApiRequest
{
    public function rules() {
        return [
            'partnerCode' => 'required|string',
            'secretKey' => 'required|string'
        ];
    }
}
