<?php

namespace App\Http\Middleware;

use App\Constants\BaseResponseTypeConstant;
use App\Modules\Auth\Services\Interfaces\AuthService;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticateEvent
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        $authService = app()->make(AuthService::class);
        if (!$authService->isLogin()) {
            if ($request->expectsJson()) {
                throw new HttpException(
                    Response::HTTP_UNAUTHORIZED,
                    __('auth.notLogin'),
                    code: BaseResponseTypeConstant::RESPONSE_TYPE_ERROR_UNAUTHENTICATED
                );
            }
            return redirect()->route('get.login');
        }
        return $next($request);
    }
}
