<?php

namespace App\Modules\Token\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'app_id' => ['required', 'string'],
            'server_ip' => ['required', 'string', 'ip'],
            'domain' => [
                'required',
                'string',
                Rule::unique('services', 'domain')->where('endpoint_domain', $this->input('endpoint_domain')),
            ],
            'endpoint_server_ip' => ['required', 'string'],
            'endpoint_domain' => ['required', 'string'],
        ];
    }
}
