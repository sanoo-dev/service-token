<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdateProfileRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'gender' => 'required|integer|in:0,1,2,3',
            'stage' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'birthday' => 'required|numeric',
            'website' => 'nullable|string'
        ];
    }
}
