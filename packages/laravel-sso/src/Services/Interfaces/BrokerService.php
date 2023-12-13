<?php

namespace TuoiTre\SSO\Services\Interfaces;

interface BrokerService
{
    public function info($name): ?array;

    public function validate($name, $publicKey): array|bool;
}
