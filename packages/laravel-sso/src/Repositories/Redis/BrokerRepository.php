<?php

namespace TuoiTre\SSO\Repositories\Redis;

use Illuminate\Support\Facades\Redis;
use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository as BrokerRepositoryInterface;

class BrokerRepository implements BrokerRepositoryInterface
{
    protected \Redis $redis;

    protected string $prefix;

    protected string $primaryAttribute;

    public function __construct()
    {
        $this->redis = Redis::connection(config('laravel-sso.redisConnection', 'default'))->client();
        $this->prefix = config('laravel-sso.redisPrefix', 'brokers_');
        $this->primaryAttribute = config('laravel-sso.primaryAttribute', 'name');
    }

    public function create(array $attributes)
    {
        if (empty($attributes[$this->primaryAttribute])) {
            throw new \RedisException('The function only accept attribute is $this->primaryAttribute');
        }
        return $this->redis->set($attributes[$this->primaryAttribute], $attributes);
    }

    public function update(int $id, array $attributes)
    {
        if ($this->primaryAttribute !== 'id') {
            throw new \RedisException(
                'The function only available on redis when $this->primaryAttribute is "id". You might consider using function "updateOrInsert" instead.'
            );
        }
        return $this->redis->set($attributes['id'], $attributes);
    }

    public function updateOrInsert(array $attributes, array $values)
    {
        if (empty($attributes[$this->primaryAttribute])) {
            throw new \RedisException('The function only accept attribute is $this->primaryAttribute');
        }
        return $this->redis->set($this->prefix . $attributes['name'], json_encode(array_merge($attributes, $values)));
    }


    public function findById(int $id)
    {
        if ($this->primaryAttribute !== 'id') {
            throw new \RedisException(
                'The function only available on redis when $this->primaryAttribute is "id". You might consider using function "findByAttributes" instead.'
            );
        }
        return @json_decode($this->redis->get($this->prefix . $id), true);
    }

    public function findByAttributes(array $attributes)
    {
        if (empty($attributes[$this->primaryAttribute])) {
            throw new \RedisException('The function only accept attribute is $this->primaryAttribute');
        }
        return @json_decode($this->redis->get($this->prefix . $attributes[$this->primaryAttribute]), true);
    }
}
