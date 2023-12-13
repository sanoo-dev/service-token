<?php

namespace TuoiTre\SSO\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use TuoiTre\SSO\Traits\ClientInfoTrait;
use TuoiTre\SSO\Traits\ResponseApiTrait;

class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ResponseApiTrait;
    use ValidatesRequests;
    use ClientInfoTrait;
}