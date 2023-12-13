<?php

namespace TuoiTre\SSO\Http\Requests;

class UpdateExtraInfoRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'birth_timestamp' => 'required_without_all:gender,stage,birthday|numeric',
            'birthday' => 'required_without_all:gender,stage,birth_timestamp|numeric',
            'gender' => 'required_without_all:birth_timestamp,stage,birthday|integer|in:0,1,2,3',
            'stage' => 'required_without_all:birth_timestamp,gender,birthday|string',
        ];
    }
}
