<?php

namespace App\Modules\Auth\Http\Middleware;

use Common\App\Models\Permission;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Redis;

class  CheckAuth
{
    public function __construct()
    {
        $this->redis_account = Redis::connection('account')->client();
        $this->redis = Redis::connection('route')->client();
    }

    public function handle($request, Closure $next)
    {
        $cookie = isset($_COOKIE['_IDT']) ? $_COOKIE['_IDT'] : null;

        if (!empty($cookie)) {
                                return $next($request);
        } else {
            \Cookie::make('_ttoauth_prod', '', time() - 3600, '/', '.tuoitre.vn');

            return redirect(env('URL_ERP_LOGIN') . env('URL_TOKEN'));
        }

//        if (!empty($cookie)) {
//            $cache_accounts = $this->redis_account->get('account::' . $cookie);
//
//            $currentURL = $_SERVER['REQUEST_URI'];
//
//            if (!empty($cache_accounts)){
//                foreach (json_decode($cache_accounts) as $key=>$cache_account){
//                    $cache_route= $this->redis->get('role::'.$cache_account);
//
//                    if (!empty($cache_route)){
//                        $temp=json_decode($cache_route);
//
//                        foreach (json_decode($cache_route) as $key=>$item)
//                        {
//                            if ($currentURL==$item) {
//                                return $next($request);
//
//                            }
//                        }
//
//                    }
//                }
//            }
//                return response()->view('auth::error.page_auth', [], 403);
//        } else {
//            \Cookie::make('_ttoauth_prod', '', time() - 3600, '/', '.tuoitre.vn');
//
//            return redirect(env('URL_ERP_LOGIN') . env('URL_TOKEN'));
//        }
    }

    // Get the value of a specific cookie
    public function getCookie($name, Request $request)
    {
        $cookieValue = $request->cookie($name);

        return $cookieValue;
    }
}
