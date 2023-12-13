<?php

namespace App\Modules\Auth\Http\Controllers;


use App\Http\Controllers\Controller;

use App\Modules\Auth\Services\Interfaces\AuthService;
use App\Modules\Token\Http\Requests\CreateTokenRequest;


use App\Modules\Token\Http\Requests\VerifyTokenRequest;
use App\Modules\Token\Services\Interfaces\ApiTokenService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function __construct(
        protected AuthService $auth,
    )
    {
    }

    public function checkAccount(Request $request)
    {
        $data = $request->all();
        return $this->auth->checkAccount($data);
    }

    public function createAccount(Request $request)
    {
        $data = $request->all();
        return $this->auth->createAccount($data);
    }

    public function loadingLogin(Request $request)
    {
        return view('auth::loading_login');
    }

    public function viewWelcome(): Factory|View|Application
    {
        return view('auth::page_welcome');
    }

    public function viewAuth(): Factory|View|Application
    {
        return view('auth::auth');
    }

    public function createRole(Request $request)
    {
        $data = $request->all();
        return $this->auth->crearteRole($data);
    }

    public function createPermission(Request $request)
    {
        $data = $request->all();
        return $this->auth->createPermission($data);
    }
}
