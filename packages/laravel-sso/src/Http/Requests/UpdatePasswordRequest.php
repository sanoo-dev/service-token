<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdatePasswordRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6',
            'reNewPassword' => 'required|same:newPassword|min:6'
        ];
    }
}
