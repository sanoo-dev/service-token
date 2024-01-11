<?php

namespace App\Modules\Token\Helpers;

use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use Illuminate\Http\JsonResponse;

class ResponseHelper implements ResponseHelperInterface
{
    protected int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    public function respondWithArray(array $array, array $headers =[]): JsonResponse
    {
        return response()->json($array, $this->statusCode, $headers);
    }

    public function respondWithMessage($message, $data = []): JsonResponse
    {
        return $this->setStatusCode(200)->respondWithArray([
            'message' => $message,
            'data' => $data,
            'code' => $this->statusCode,
        ]);
    }

    public function respondMetaWithMessage($message, $data = [], $meta = []): JsonResponse
    {
        return $this->setStatusCode(200)->respondWithArray([
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
            'code' => $this->statusCode,
        ]);
    }

    public function respondValidationErr($errors): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithArray([
            'error' => $errors,
            'code' => $this->statusCode,
        ]);
    }

    public function respondValidationErrWithData($errors, $data = []): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithArray([
            'error' => $errors,
            'data' => $data,
            'code' => $this->statusCode,
        ]);
    }

    public function respondWithGetId($message, $id): JsonResponse
    {
        return $this->setStatusCode(200)->respondWithArray([
            'message' => $message,
            'code' => $this->statusCode,
            'id' => $id
        ]);
    }

    protected function respondWithError($message, $errorCode, $errors = []): JsonResponse
    {
        if($this->statusCode === 200){
            trigger_error("You better have a really good reason for erroring on a 200...",
                E_USER_WARNING);
        }

        return $this->respondWithArray([
            'errors' => $errors,
            'code' => $errorCode,
            'message' => $message
        ]);
    }

    public function errorForbidden($message = 'Forbidden', $errors = []): JsonResponse
    {
        return $this->setStatusCode(500)->respondWithError($message, $this->statusCode, $errors);
    }

    public function errorNotfound($message = 'Resource Not Found', $errors = []): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithError($message, $this->statusCode, $errors);
    }

    public function errorUnauthorized($message = 'Unauthorized', $errors =[]): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithError($message, $this->statusCode, $errors);
    }

    /**
     * @param $message
     * @param $errors
     * @return JsonResponse
     */
    public function errorInternalError($message = 'Internal Error', $errors = []): JsonResponse
    {
        return $this->setStatusCode(500)->respondWithError($message, $this->statusCode, $errors);
    }
    /**
     * @param $success
     * @param $status
     * @param $data
     * @return JsonResponse
     */
    public  function responseError($success, $status, $data=null): JsonResponse
    {
        return $this->respondWithArray([
            'success' => $success,
            'status' =>$status,
            'data'=>$data

        ]);
    }
    /**
     * @param $message
     * @param $status
     * @param $data
     * @return JsonResponse
     */
    public function responseMess($message, $status, $data = null): mixed
    {
        return $this->respondWithArray([
            'message' => $message,
            'status' =>$status,
            'data'=>$data

        ]);
    }
}
