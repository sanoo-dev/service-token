<?php

namespace TuoiTre\SSO\Services\Interfaces;

interface EventService
{
    public function buyTicket(string $id, string $codeEvent, string $name, string $email, string $phone): ?array;
}
