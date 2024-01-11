<?php

namespace App\Modules\Token\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEndpointRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'server_ip' => ['required', 'string'],
            'domain' => [
                'required',
                'string',
                Rule::unique('endpoints', 'domain')->where('server_ip', $this->input('server_ip')),
            ],
        ];
    }
}
