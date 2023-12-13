<?php

namespace App\Traits;

use App\Constants\BaseResponseTypeConstant;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public function responseJsonSuccess(array $data = []): JsonResponse
    {
        return response()->json(
            array_merge($data, ['success' => true, 'code' => Response::HTTP_OK, 'type' => BaseResponseTypeConstant::RESPONSE_TYPE_SUCCESS]),
            Response::HTTP_OK
        );
    }

    public function responseJsonError(array $data = [], int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(array_merge($data, ['success' => false, 'code' => $statusCode]), $statusCode);
    }
}
