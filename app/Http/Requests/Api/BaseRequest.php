<?php

namespace App\Http\Requests\Api;

use App\Constants\BaseResponseTypeConstant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
//        $list_errors = [];
//        foreach ($errors as $key => $error) {
//            if (!empty($error[0])) {
//                $list_errors[$key] = $error[0];
//            }
//        }

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('base.paramsInvalid'),
                'error' => array_values($errors)[0][0] ?? __('base.paramsInvalid'),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'type' => BaseResponseTypeConstant::RESPONSE_TYPE_ERROR_PARAMS_INVALID
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}