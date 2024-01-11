<?php

namespace App\Modules\Auth\Policies;

class EndpointPolicy
{
    public function list(): bool
    {
        return true;
    }
}
