<?php

namespace App\Token\Http\Requests;

use Api\Http\Requests\ApiRequest;

class TestRequest extends ApiRequest
{
    public function rules() {
        return [
            'partnerCode' => 'string',

        ];
    }
}
