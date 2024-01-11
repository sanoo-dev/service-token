<?php

namespace App\Modules\Token\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Token\Helpers\Interfaces\ResponseHelperInterface;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndpointRepositoryInterface;
use App\Modules\Token\Services\Interfaces\EndpointServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiEndpointController extends Controller
{
    /**
     * @param EndpointServiceInterface $endpointService
     * @param EndpointRepositoryInterface $endpointRepository
     * @param ResponseHelperInterface $responseHelper
     */
    public function __construct(
        protected EndpointServiceInterface    $endpointService,
        protected EndpointRepositoryInterface $endpointRepository,
        protected ResponseHelperInterface $responseHelper,
    )
    {
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id): JsonResponse
    {
        $requestData = ['id' => $id];

        $responseData = $this->endpointService->getOneEndpoint($requestData);

        return $this->responseHelper->responseMess(...$responseData);
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
        ]);

        $responseData = $this->endpointService->getListEndpoint(1, 1000, $requestData);

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
            'status',
        ]);

        $responseData = $this->endpointService->update($requestData, $id);

        return $this->responseHelper->responseMess(...$responseData);
    }
}
