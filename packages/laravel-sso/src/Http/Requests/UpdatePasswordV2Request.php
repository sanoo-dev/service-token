<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdatePasswordV2Request extends ApiRequest
{
    public function rules()
    {
        return [
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6',
        ];
    }
}
