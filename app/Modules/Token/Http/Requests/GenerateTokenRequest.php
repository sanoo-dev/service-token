<?php

namespace App\Modules\Token\Http\Requests;

use TuoiTre\SSO\Http\Requests\ApiRequest;

class GenerateTokenRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'private_key' => ['required', 'string'],
            'partner_code' => ['required', 'string'],
        ];
    }
}
