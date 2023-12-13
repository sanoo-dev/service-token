<?php

namespace TuoiTre\SSO\Http\Requests;

class DonateRequest extends ApiRequest
{
    public function rules() {
        return [
            "numberOfStars" => 'nullable|required:star|int|min:1',
            "articleLink" => 'required|url',
        ];
    }
}
