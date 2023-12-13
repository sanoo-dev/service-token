<?php

namespace App\Modules\Token\Http\Requests;



use TuoiTre\SSO\Http\Requests\ApiRequest;

class CreateServiceRequest extends ApiRequest
{
    public function rules() {
        return [
            'domain' => 'required|string',
            'serveIp' => 'required|string',
            'domainTransfer' => 'required|string',
            'serveIpTransfer' => 'required|string',
            'appId' => 'required|string',
            'appName' => 'required|string',

        ];
    }
}
