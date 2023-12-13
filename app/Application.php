<?php


use Common\Application as CommonApplication;

class Application extends CommonApplication
{
    public function __construct(?string $basePath = null)
    {
        parent::__construct($basePath);
        $this->useDatabasePath(realpath(__DIR__ . '/database'));
    }
}
