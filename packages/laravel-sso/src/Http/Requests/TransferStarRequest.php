<?php

namespace TuoiTre\SSO\Http\Requests;

class TransferStarRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'payment_type' => 'required',
            'member_id' => 'required',
            'app_id' => 'required',
            'star' => 'required',
            'type_info' => 'nullable',
            'phone_or_email' => 'nullable',

            'to_member_id' => 'nullable',
            'article_link' => 'nullable',
            'name' => 'nullable',
            'comment_content' => 'nullable',

            'type' => 'required',
            'otp' => 'nullable'
        ];
    }
}
