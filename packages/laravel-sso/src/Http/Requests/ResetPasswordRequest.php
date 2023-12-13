<?php

namespace TuoiTre\SSO\Http\Requests;

class ResetPasswordRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'emailOrPhone' => 'required|string'
        ];
    }
}
