<?php

namespace TuoiTre\SSO\Traits;

trait SessionTrait
{
    protected function getSession(string $key): mixed
    {
        return $key === 'id' ? session()->getId() : session()->get($key, null);
    }

    protected function setSession(string $key, array|string $value = null): void
    {
        if ($value !== null) {
            session()->put($key, $value);
        } else {
            session()->forget($key);
        }
    }
}
