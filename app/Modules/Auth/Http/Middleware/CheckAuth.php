<?php

namespace App\Modules\Auth\Http\Middleware;

use Closure;

class CheckAuth
{
    public function handle($request, Closure $next)
    {
        $cookie = $request->cookie('_ttoauth_prod');

        if (!empty($cookie)) {
            return $next($request);
        } else {
            \Cookie::make('_ttoauth_prod', '', time() - 3600, '/', '.tuoitre.vn');
            return redirect(env('URL_ERP_LOGIN') . env('URL_TOKEN'));
        }
    }
}
