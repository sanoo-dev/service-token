<?php

namespace TuoiTre\SSO\Providers;

use Firebase\JWT\JWT;
use Fruitcake\Cors\HandleCors;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TuoiTre\SSO\Console\Commands\BuildBrokerCommand;
use TuoiTre\SSO\Console\Commands\CreateBrokerCommand;
use TuoiTre\SSO\Console\Commands\UpdateBrokerCommand;
use TuoiTre\SSO\Exceptions\ApiHandler;
use TuoiTre\SSO\Http\Middleware\StartBrokerSession;
use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository as BrokerRepositoryInterface;
use TuoiTre\SSO\Repositories\Redis\BrokerRepository as RedisBrokerRepository;
use TuoiTre\SSO\Services\BrokerService;
use TuoiTre\SSO\Services\CacheService;
use TuoiTre\SSO\Services\Interfaces\BrokerService as BrokerServiceInterface;
use TuoiTre\SSO\Services\Interfaces\MemberService as MemberServiceInterface;
use TuoiTre\SSO\Services\Interfaces\CacheService as CacheServiceInterface;
use TuoiTre\SSO\Services\Interfaces\SSOServerService as SSOServerServiceInterface;
use TuoiTre\SSO\Services\Interfaces\PaymentService as PaymentServiceInterface;
use TuoiTre\SSO\Services\MemberService;
use TuoiTre\SSO\Services\PaymentService;
use TuoiTre\SSO\Services\SSOServerService;

class SSOServiceProvider extends ServiceProvider
{
    protected string $controllerNamespace = 'TuoiTre\SSO\Http\Controllers';

    public function register()
    {
        JWT::$leeway = 60;
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-sso.php', 'laravel-sso');
        $this->mergeConfigFrom(__DIR__ . '/../../config/cors.php', 'cors');
//        config()->set('cors', require __DIR__ . '/../../config/cors.php');

        config([
            'services.google' => [
                'client_id' => env(
                    'GG_CLIENT_ID',
                    '324436088829-kvrkutdrd8pfkfost9nigp0p2vava1rv.apps.googleusercontent.com'
                ),
                'client_secret' => env('GG_CLIENT_SECRET', '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'),
                'redirect' => env('GG_REDIRECT_URL', 'http://localhost/sso/v1/social-login/callback/google')
            ]
        ]);

        config([
            'services.facebook' => [
                'client_id' => env('FB_CLIENT_ID', '827033221918751'),
                'client_secret' => env('FB_CLIENT_SECRET', '68d1ab42f565315e118f11cf06babf2d'),
                'redirect' => env('FB_REDIRECT_URL', 'http://localhost/sso/v1/social-login/callback/facebook')
            ]
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        /**
         * @var Router $router
         */
        $router = $this->app['router'];
        $router->middlewareGroup('sso-api', [
            HandleCors::class,
            SubstituteBindings::class,
//            'throttle:sso-api'
        ]);
        $router->middlewareGroup('broker-session', [
            StartBrokerSession::class
        ]);

        Route::middleware(config('laravel-sso.middleware', 'sso-api'))
            ->namespace($this->controllerNamespace)
            ->group(__DIR__ . '/../../routes/api.php');

        $this->app->bind(SSOServerServiceInterface::class, SSOServerService::class);
        $this->app->bind(MemberServiceInterface::class, MemberService::class);
        $this->app->bind(BrokerServiceInterface::class, BrokerService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);

        $this->app->bind(BrokerRepositoryInterface::class, RedisBrokerRepository::class);
//        $this->app->singleton(ExceptionHandler::class, ApiHandler::class);

        $this->commands([
            CreateBrokerCommand::class,
            UpdateBrokerCommand::class,
            BuildBrokerCommand::class
        ]);
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'sso');

//        RateLimiter::for('sso-api', function (Request $request) {
//            return Limit::perMinute(config('laravel-sso.maxAttemptsOnMinutes', 1000))->by($request->ip());
//        });
        $this->publishes([__DIR__ . '/../../config/laravel-sso.php' => config_path('laravel-sso.php')], 'config');
        $this->publishes([__DIR__ . '/../../config/cors.php' => config_path('cors.php')], 'config');
        $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/sso')]);
    }
}
