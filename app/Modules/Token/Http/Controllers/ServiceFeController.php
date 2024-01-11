<?php

namespace App\Modules\Token\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ServiceFeController extends Controller
{
    public function viewLogin(): Factory|View|Application
    {
        return view('token::login');
    }

    public function viewWelcome(): Factory|View|Application
    {
        return view('token::layouts.page_welcome');
    }

    public function viewManageUser(): Factory|View|Application
    {
        return view('token::user.index');
    }

    public function viewCreateUser(): Factory|View|Application
    {
        return view('token::create.user');
    }

    public function viewDetailUser(): Factory|View|Application
    {
        return view('token::user.detail');
    }

}
