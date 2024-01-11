<?php

namespace App\Modules\Token\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Http\Requests\GenerateTokenRequest;
use App\Modules\Token\Http\Requests\VerifyTokenRequest;
use App\Modules\Token\Services\Interfaces\TokenServiceInterface;
use Illuminate\Http\JsonResponse;

class ApiTokenController extends Controller
{
    /**
     * @param TokenServiceInterface $tokenService
     * @param ResponseHelperInterface $responseHelper
     */
    public function __construct(
        protected TokenServiceInterface $tokenService,
        protected ResponseHelperInterface $responseHelper,
    )
    {
    }

    /**
     * @param GenerateTokenRequest $request
     * @return JsonResponse
     */
    public function generate(GenerateTokenRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        $responseData = $this->tokenService->generate($requestData);

        return $this->responseHelper->responseMess(...$responseData);
    }


    /**
     * @param VerifyTokenRequest $request
     * @return JsonResponse
     */
    public function verify(VerifyTokenRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        $responseData = $this->tokenService->verify($requestData);

        return $this->responseHelper->responseMess(...$responseData);
    }
}
