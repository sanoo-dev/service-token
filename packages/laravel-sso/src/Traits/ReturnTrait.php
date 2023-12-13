<?php

namespace TuoiTre\SSO\Traits;

use TuoiTre\SSO\Constants\ResponseTypeConstant;

trait ReturnTrait
{
    protected function returnArrSuccess(array $data = []): array
    {
        return array_merge($data, ['type' => ResponseTypeConstant::TYPE_SUCCESS]);
    }

    protected function returnArrError(array $data = [], int $type = ResponseTypeConstant::TYPE_ERROR_OTHER): array
    {
        return array_merge($data, ['type' => $type]);
    }

    protected function returnBoolSuccess(): bool
    {
        return true;
    }

    protected function returnBoolError(): bool
    {
        return false;
    }
}
