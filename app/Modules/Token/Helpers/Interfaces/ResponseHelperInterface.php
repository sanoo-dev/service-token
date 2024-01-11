<?php

namespace App\Modules\Token\Helpers\Interfaces;

interface ResponseHelperInterface
{
    /**
     * @return mixed
     */
    public function getStatusCode();

    /**
     * @param $statusCode
     * @return mixed
     */
    public function setStatusCode($statusCode);

    /**
     * @return mixed
     */
    public function noContent();

    /**
     * @param array $array
     * @param array $headers
     * @return mixed
     */
    public function respondWithArray(array $array, array $headers): mixed;

    /**
     * @param $message
     * @param $data
     * @return mixed
     */
    public function respondWithMessage($message, $data): mixed;

    /**
     * @param $message
     * @param $data
     * @return mixed
     */
    public function respondMetaWithMessage($message, $data, $meta): mixed;

    /**
     * @param $errors
     * @return mixed
     */
    public function respondValidationErr($errors): mixed;

    /**
     * @param $errors
     * @param $data
     * @return mixed
     */
    public function respondValidationErrWithData($errors, $data): mixed;

    /**
     * @param $message
     * @param $id
     * @return mixed
     */
    public function respondWithGetId($message, $id): mixed;

    /**
     * @param $message
     * @param array $errors
     * @return mixed
     */
    public function errorForbidden($message, array $errors): mixed;

    /**
     * @param $message
     * @param array $errors
     * @return mixed
     */
    public function errorNotfound($message, array $errors): mixed;

    /**
     * @param $message
     * @param array $errors
     * @return mixed
     */
    public function errorUnauthorized($message, array $errors): mixed;

    /**
     * @param $message
     * @param array $errors
     * @return mixed
     */
    public function errorInternalError($message, array $errors): mixed;
    /**
     * @param $success
     * @param  $errors
     * @param null $data
     * @return mixed
     */
    public function responseError($success,  $errors,$data=null): mixed;
    /**
     * @param $message
     * @param  $errors
     * @param null $data
     * @return mixed
     */
    public function responseMess($message,  $errors,$data=null): mixed;
}
