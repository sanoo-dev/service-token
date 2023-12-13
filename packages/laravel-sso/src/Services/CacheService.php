<?php

namespace TuoiTre\SSO\Services;

use Illuminate\Contracts\Redis\Factory;
use Illuminate\Support\Facades\Log;
use Redis;
use TuoiTre\SSO\Services\Interfaces\CacheService as CacheServiceInterface;

class CacheService implements CacheServiceInterface
{
    private Redis $connection;

    private int $expire;

    public function __construct(
        protected Factory $redis
    ) {
        $this->connection = $this->redis->connection(config('laravel-sso.redisConnection', 'default'))->client();
        $this->expire = config('laravel-sso.cacheExpiredMinutes', 1);
    }

    public function set(string $key, string $value, ?int $expire = null): bool
    {
        try {
            $this->connection->setEx(
                "cache::$key",
                $expire ?? ($this->expire * 60),
                $value
            );
            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function get(string $key)
    {
        try {
            return $this->connection->get("cache::$key");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }
}