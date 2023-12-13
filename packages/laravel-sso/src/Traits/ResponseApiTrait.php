<?php

namespace TuoiTre\SSO\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseApiTrait
{
    public function responseSuccess(array $data = []): JsonResponse
    {
        return response()->json(array_merge($data, ['code' => Response::HTTP_OK]), Response::HTTP_OK);
    }

    public function responseError(array $data = [], int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(array_merge($data, ['code' => $statusCode]), $statusCode);
    }
}
