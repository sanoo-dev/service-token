<?php

namespace Common;

use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Foundation\PackageManifest;
use RuntimeException;

class Application extends BaseApplication
{
    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace()
    {
        if (!is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath . '/../composer.json'), true);

        foreach ((array)data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array)$path as $pathChoice) {
                if (realpath($this->path()) === realpath($this->basePath($pathChoice))) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();

        $PackageManifest = app()->make(PackageManifest::class);
        $PackageManifest->basePath = dirname($this->basePath());
        $PackageManifest->vendorPath =realpath($this->basePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor');
    }

    /**
     * Use get value of absoluteCachePathPrefixes
     * Use in \Common\Bootstrap\LoadConfiguration
     *
     * @return string[]
     */
    public function getAbsoluteCachePathPrefixes(): array
    {
        return $this->absoluteCachePathPrefixes;
    }

    /**
     * Use to debug list serviceProvider
     * @return void
     */
    public function getAllServiceProvider() {
        foreach ($this->serviceProviders as $value) {
            print_r(get_class($value) . "\n");
        }
    }
}
