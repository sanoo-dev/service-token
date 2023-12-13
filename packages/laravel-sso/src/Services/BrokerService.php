<?php

namespace TuoiTre\SSO\Services;

use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository;
use TuoiTre\SSO\Services\Interfaces\BrokerService as BrokerServiceInterface;

class BrokerService implements BrokerServiceInterface
{
    public function __construct(
        protected BrokerRepository $brokerRepository
    ) {
    }

    public function info($name): ?array
    {
        if (!empty($broker = $this->brokerRepository->findByAttributes(['name' => $name]))) {
            return collect($broker)->toArray();
        }
        return null;
    }

    public function validate($name, $publicKey): array|bool
    {
        if (!empty(
            $broker = $this->brokerRepository->findByAttributes(['name' => $name])
            ) && ($broker['public_key'] === $publicKey)) {
            return collect($broker)->toArray();
        }
        return false;
    }
}
