<?php

namespace App\Modules\Token\Http\Controllers;


use App\Http\Controllers\Controller;

use App\Modules\Token\Http\Requests\CreateTokenRequest;


use App\Modules\Token\Http\Requests\VerifyTokenRequest;
use App\Modules\Token\Services\Interfaces\ApiTokenService;

use Illuminate\Http\Request;


class ApiTokenController extends Controller
{
    public function __construct (
    )
    {
    }
    public function createAccount(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->createAccount($data);
    }
    public function createToken(ApiTokenService  $tokenService, CreateTokenRequest $request) {

        $data = $request->all();
        return $tokenService->createToken_cache($data);
    }
    public function verifyToken(ApiTokenService  $tokenService, VerifyTokenRequest $request) {

        $data = $request->all();
        return $tokenService->verifyToken_cache($data);
    }
    public function saveService(ApiTokenService  $tokenService, CreateServiceRequest $request) {

        $data = $request->all();
        return $tokenService->saveInfoService($data);
    }
    public function saveInfoTransfer(ApiTokenService  $tokenService, CreateEndPointRequest $request) {

        $data = $request->all();
        return $tokenService->saveInfoTransfer($data);
    }

    public function updateService(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->updateService($data);
    }
    public function updateTransfer(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->updateTransfer($data);
    }
    public function acceptEndPoint(ApiTokenService  $tokenService, TestRequest $request) {

        $data = $request->all();
        return $tokenService->acceptEndPoint($data);
    }
    public function acceptService(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();

        return $tokenService->acceptService($data);
    }
    public function extendKey(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->extendKey($data);
    }
    public function getListEndPoint(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->getListEndPoint($data);
    }
    public function getListService(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->getListService($data);
    }
    public function createNewKey(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->createNewKey($data);
    }
    public function acceptKey(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->acceptKey($data);
    }
    public function delData(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->delData($data);
    }
    public function addServicetoEP(ApiTokenService  $tokenService, Request $request) {

        $data = $request->all();
        return $tokenService->serviceaddED($data);
    }

}
