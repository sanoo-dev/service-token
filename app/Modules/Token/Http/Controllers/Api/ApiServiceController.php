<?php

namespace App\Modules\Token\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepositoryInterface;
use App\Modules\Token\Services\Interfaces\ServiceServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiServiceController extends Controller
{
    /**
     * @param ServiceServiceInterface $serviceService
     * @param ServiceRepositoryInterface $serviceRepository
     * @param ResponseHelperInterface $responseHelper
     */
    public function __construct(
        protected ServiceServiceInterface $serviceService,
        protected ServiceRepositoryInterface $serviceRepository,
        protected ResponseHelperInterface $responseHelper,
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $requestData = $request->only([
            'id',
            'domain',
            'server_ip',
            'endpoint_domain',
            'endpoint_server_ip',
        ]);

        $responseData = $this->serviceService->getListService(1, 1000, $requestData);

        return $this->responseHelper->responseMess(...$responseData);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id): JsonResponse
    {
        $requestData = ['id' => $id];

        $responseData = $this->serviceService->getOneService($requestData);

        return $this->responseHelper->responseMess(...$responseData);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $requestData = $request->only([
            'domain',
            'server_ip',
            'endpoint_domain',
            'endpoint_server_ip',
            'status',
        ]);

        $responseData = $this->serviceService->update($requestData, $id);

        return $this->responseHelper->responseMess(...$responseData);
    }
}
