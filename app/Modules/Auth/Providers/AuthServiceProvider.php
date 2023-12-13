<?php

namespace App\Modules\Auth\Providers;
use App\Modules\Auth\Services\AuthService;
use Common\app\Indexes\AccountIndex;

use App\Modules\Auth\Repositories\Elasticsearch\AccountRepository as AccountElasticsearchRepository;
use App\Modules\Auth\Repositories\Elasticsearch\RoleRepository as RoleElasticsearchRepository;
use App\Modules\Auth\Repositories\Elasticsearch\PermissionRepository as PermissionElasticsearchRepository;

use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository as AccountElasticsearchRepositoryInterface;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\RoleRepository as RoleElasticsearchRepositoryInterface;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\PermissionRepository as PermissionElasticsearchRepositoryInterface;
use Common\App\Indexes\PermissionIndex;
use Common\App\Indexes\RoleIndex;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Modules\Auth\Services\Interfaces\AuthService as AuthServiceInterface;


class AuthServiceProvider extends ServiceProvider
{
//    use RegisterRouteTrait;
    protected string $moduleName = 'auth';

    protected string $controllerNamespace = 'App\Modules\Auth\Http\Controllers';

    public function register()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../Resources/views'), $this->moduleName);
        $this->registerRoutes($this->controllerNamespace, __DIR__ . '/../Routes/web.php');
        $this->registerApiRoutes($this->controllerNamespace, __DIR__ . '/../Routes/api.php');


        $this->app->bind(AccountElasticsearchRepositoryInterface::class, function () {
            return new AccountElasticsearchRepository(new AccountIndex());
        });
        $this->app->bind(RoleElasticsearchRepositoryInterface::class, function () {
            return new RoleElasticsearchRepository(new RoleIndex());
        });
        $this->app->bind(PermissionElasticsearchRepositoryInterface::class, function () {
            return new PermissionElasticsearchRepository(new PermissionIndex());
        });

//
//        $this->app->bind(ServiceRepositoryInterface::class, function () {
//            return new ServiceRepository(new \Common\App\Models\Service());
//        });
//        $this->app->bind(AccountRepositoryInterface::class, function () {
//            return new AccountRepository(new Account());
//        });


        $this->app->bind(AuthServiceInterface::class,AuthService::class);

//        $this->app->bind(ResponseHelperInterface::class,ResponseHelper::class);


    }
    protected function registerRoutes($controllerNamespace, $routeFilePath): void
    {
        Route::middleware(['web'])
//            ->namespace($controllerNamespace)
            ->group($routeFilePath);
    }

    protected function registerApiRoutes($controllerNamespace, $routeFilePath): void
    {
        Route::middleware(['api'])
//            ->namespace($controllerNamespace)
            ->group($routeFilePath);
    }
}
