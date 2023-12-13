<?php

namespace TuoiTre\SSO\Traits;

use Illuminate\Support\Facades\Redis;

trait BrokerSessionTrait
{
    protected ?\Redis $redisConnection = null;

    public function setRedisConnection(string $connection = 'default'): void
    {
        $this->redisConnection = Redis::connection($connection)->client();
    }

    protected function redisConnection(): \Redis
    {
        if (is_null($this->redisConnection)) {
            $this->setRedisConnection();
        }
        return $this->redisConnection;
    }

    protected function generateBrokerSessionData(
        string $memberId = null,
        int $expiredMinutes = null,
        array $options = []
    ): array {
        return array_merge([
            'startAt' => time(),
            'expiredAfterMinutes' => (int)($expiredMinutes ?? config('laravel-sso.defaultExpiredMinutes', 60)),
            'memberId' => $memberId
        ], $options);
    }

    protected function setBrokerSessionData(string $key, array $data): void
    {
        $expired = ($data['expiredAfterMinutes'] ?? config('laravel-sso.defaultExpiredMinutes', 60)) * 60
            + config('laravel-sso.defaultKeepTokenAfterExpiredMinutes', 60) * 60;
        $this->redisConnection()->setEx(
            "broker_session::$key",
            $expired,
            @json_encode($data)
        );
    }

    protected function deleteBrokerSessionData($key): void
    {
        $this->redisConnection()->del("broker_session::$key");
    }

    protected function getBrokerSessionData($key)
    {
        return @json_decode($this->redisConnection()->get("broker_session::$key"), true);
    }
}
