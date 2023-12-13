<?php

namespace TuoiTre\SSO\Http\Requests;

class CheckTokenPasswordRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'token' => 'required|string',
        ];
    }
}
