<?php

namespace App\Modules\Token\Providers;
use app\Modules\Token\Repositories\Eloquent\AccountRepository;
use App\Modules\Token\Services\ApiTokenService;
use app\Modules\Token\Services\TokenService;
use Common\app\Indexes\AccountIndex;
use Common\App\Indexes\EndPointIndex;
use Common\app\Indexes\PendingIndex;
use Common\App\Indexes\ServiceIndex;
use App\Modules\Token\Repositories\Elasticsearch\EndPointRepository as PointElasticsearchRepository;

use App\Modules\Token\Repositories\Elasticsearch\PendingRepository as PendingElasticsearchRepository;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndPointRepository as PointElasticsearchRepositoryInterface;

use App\Modules\Token\Repositories\Elasticsearch\Interfaces\PendingRepository as PendingElasticsearchRepositoryInterface;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepository as ServiceElasticsearchRepositoryInterface;
use App\Modules\Token\Services\Interfaces\ApiTokenService as TokenServiceInterface;

use App\Modules\Token\Repositories\Elasticsearch\ServiceRepository as ServiceElasticsearchRepository;

class TokenServiceProvider extends ServiceProvider
{
//    use RegisterRouteTrait;
    protected string $moduleName = 'token';

    protected string $controllerNamespace = 'App\Modules\Token\Http\Controllers';

    public function register()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../Resources/views'), $this->moduleName);
        $this->registerRoutes($this->controllerNamespace, __DIR__ . '/../Routes/web.php');
        $this->registerApiRoutes($this->controllerNamespace, __DIR__ . '/../Routes/api.php');
        $this->app->bind(ServiceElasticsearchRepositoryInterface::class, function () {
            return new ServiceElasticsearchRepository(new  ServiceIndex());
        });
        $this->app->bind(PointElasticsearchRepositoryInterface::class, function () {
            return new PointElasticsearchRepository(new EndPointIndex());
        });

//        $this->app->bind(AccountElasticsearchRepositoryInterface::class, function () {
//            return new AccountElasticsearchRepository(new AccountIndex());
//        });
        $this->app->bind(PendingElasticsearchRepositoryInterface::class, function () {
            return new PendingElasticsearchRepository(new PendingIndex());
        });
//
//        $this->app->bind(ServiceRepositoryInterface::class, function () {
//            return new ServiceRepository(new \Common\App\Models\Service());
//        });
//        $this->app->bind(AccountRepositoryInterface::class, function () {
//            return new AccountRepository(new Account());
//        });


        $this->app->bind(TokenServiceInterface::class,ApiTokenService::class);

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
