<?php

namespace App\Modules\Token\Providers;

use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Helpers\ResponseHelper;
use App\Modules\Token\Repositories\Elasticsearch\EndpointRepository;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndpointRepositoryInterface;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepositoryInterface;
use App\Modules\Token\Repositories\Elasticsearch\ServiceRepository;
use App\Modules\Token\Services\EndpointService;
use App\Modules\Token\Services\Interfaces\EndpointServiceInterface;
use App\Modules\Token\Services\Interfaces\ServiceServiceInterface;
use App\Modules\Token\Services\Interfaces\TokenServiceInterface;
use App\Modules\Token\Services\ServiceService;
use App\Modules\Token\Services\TokenService;
use Common\App\Indexes\EndpointIndex;
use Common\App\Indexes\ServiceIndex;
use Illuminate\Support\ServiceProvider;

class TokenServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'token';

    public function register(): void
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../Resources/views'), $this->moduleName);

        // Service
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
        $this->app->bind(EndpointServiceInterface::class, EndpointService::class);
        $this->app->bind(TokenServiceInterface::class, TokenService::class);

        // Repository
        $this->app->bind(ServiceRepositoryInterface::class, function () {
            return new ServiceRepository(new ServiceIndex());
        });

        $this->app->bind(EndpointRepositoryInterface::class, function () {
            return new EndpointRepository(new EndpointIndex());
        });

        // Helper
        $this->app->bind(ResponseHelperInterface::class, ResponseHelper::class);
    }
}
