<?php

namespace App\Modules\Token\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $apiControllerNamespace = 'App\Modules\Token\Http\Controllers\Api';
    protected string $controllerNamespace = 'App\Modules\Token\Http\Controllers';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->apiControllerNamespace)
                ->group(__DIR__ . '/../Routes/api.php');

            Route::middleware('web')
                ->namespace($this->controllerNamespace)
                ->group(__DIR__ . '/../Routes/web.php');
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(env('RATE_LIMIT_PER_MINUTE', 120))->by($request->ip());
        });
    }
}
