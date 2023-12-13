<?php

namespace TuoiTre\SSO\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use TuoiTre\SSO\Services\Interfaces\SSOServerService;
use TuoiTre\SSO\Traits\ResponseApiTrait;

class StartBrokerSession
{
    use ResponseApiTrait;

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var SSOServerService $SSOServerService
         * */
        $SSOServerService = app()->make(SSOServerService::class);
        $startBroker = $SSOServerService->startBrokerSession();
        if ($startBroker !== true) {
            $statusCode = $startBroker['errorStatusCode'] ?? Response::HTTP_UNAUTHORIZED;
            unset($startBroker['errorStatusCode']);
            return $this->responseError($startBroker, $statusCode);
        }
        return $next($request);
    }
}
