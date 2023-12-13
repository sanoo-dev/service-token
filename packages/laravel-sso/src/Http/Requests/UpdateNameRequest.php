<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdateNameRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'password' => 'required|string|min:6',
            'name' => 'required|string',
        ];
    }
}
