<?php

namespace TuoiTre\SSO\Http\Requests;

class OTPTransferRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'member_id' => 'required',
            'type' => 'required',
            'type_info' => 'required',

            'phone_or_email' => 'required_without_all:to_member_id,name,star,article_link,comment_content',

            'name' => 'required_without:phone_or_email',
            'star' => 'required_without:phone_or_email',
            'article_link' => 'required_without:phone_or_email',
            'comment_content' => 'required_without:phone_or_email',
            'to_member_id' => 'nullable',
        ];
    }
}
