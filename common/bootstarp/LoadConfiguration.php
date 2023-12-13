<?php

namespace Common\Bootstrap;

use Exception;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as BaseLoadConfiguration;
use Symfony\Component\Finder\Finder;

class LoadConfiguration extends BaseLoadConfiguration
{
    /**
     * Load the configuration items from all of the files.
     *
     * @param Application $app
     * @param RepositoryContract $repository
     * @return void
     *
     * @throws Exception
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $repository)
    {
        $commonFiles = $this->getCommonConfigurationFiles($app);
        $files = $this->getConfigurationFiles($app);

        $configs = [];

        foreach ($commonFiles as $commonKey => $commonPath) {
            $configs[$commonKey] = require $commonPath;
        }

        foreach ($files as $key => $path) {
            $config_from_file = require $path;
            $configs[$key] = isset($configs[$key]) ? array_merge($configs[$key], $config_from_file) : $config_from_file;
        }

        if (!isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }
        foreach ($configs as $key => $value) {
            $repository->set($key, $value);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @param Application $app
     * @return array
     */
    protected function getCommonConfigurationFiles(Application $app): array
    {
        $files = [];

        $configPath = realpath($app->basePath() . '/../common/config/');

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory . basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }
}
