<?php

namespace TuoiTre\SSO\Http\Requests;

class BuyTicketRequest extends ApiRequest
{
    public function rules() {
        return [
            "id" => 'required',
            "code_event" => 'required',
            "name" => 'required',
            "email" => 'required|email:rfc,filter',
            'phone' => 'nullable',
        ];
    }
}
