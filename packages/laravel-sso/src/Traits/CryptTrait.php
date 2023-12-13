<?php

namespace TuoiTre\SSO\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

trait CryptTrait
{
    public function encrypt($value): ?string
    {
        try {
            return Crypt::encrypt($value);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }

    public function decrypt($payload)
    {
        try {
            return Crypt::decrypt($payload);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }
}