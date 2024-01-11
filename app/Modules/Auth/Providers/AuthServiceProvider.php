<?php

namespace App\Modules\Auth\Providers;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\PermissionRepositoryInterface;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\RoleRepositoryInterface;
use App\Modules\Auth\Repositories\Elasticsearch\PermissionRepository;
use App\Modules\Auth\Repositories\Elasticsearch\RoleRepository;
use App\Modules\Auth\Services\AuthService;
use Common\app\Indexes\AccountIndex;
use App\Modules\Auth\Repositories\Elasticsearch\AccountRepository as AccountElasticsearchRepository;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository as AccountElasticsearchRepositoryInterface;
use Common\App\Indexes\PermissionIndex;
use Common\App\Indexes\RoleIndex;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Modules\Auth\Services\Interfaces\AuthService as AuthServiceInterface;

class AuthServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'auth';

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('list-endpoint', function () {
            return true;
        });
    }

    public function register()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../Resources/views'), $this->moduleName);


        $this->app->bind(AccountElasticsearchRepositoryInterface::class, function () {
            return new AccountElasticsearchRepository(new AccountIndex());
        });

        $this->app->bind(RoleRepositoryInterface::class, function () {
            return new RoleRepository(new RoleIndex());
        });

        $this->app->bind(PermissionRepositoryInterface::class, function () {
            return new PermissionRepository(new PermissionIndex());
        });

        $this->app->bind(AuthServiceInterface::class,AuthService::class);

//        $this->app->bind(ResponseHelperInterface::class,ResponseHelper::class);
    }
}
