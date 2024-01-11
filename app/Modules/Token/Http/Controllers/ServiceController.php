<?php

namespace App\Modules\Token\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Token\Helpers\Constants\ConstantDefine;
use App\Modules\Token\Helpers\Constants\MessageResponseCode;
use App\Modules\Token\Http\Requests\StoreServiceRequest;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\ServiceRepositoryInterface;
use App\Modules\Token\Services\Interfaces\EndpointServiceInterface;
use App\Modules\Token\Services\Interfaces\ServiceServiceInterface;
use App\Modules\Token\Traits\KeyRenaming;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceController extends Controller
{
    use KeyRenaming;

    /**
     * @param ServiceServiceInterface $serviceService
     * @param ServiceRepositoryInterface $serviceRepository
     * @param EndpointServiceInterface $endpointService
     */
    public function __construct(
        protected ServiceServiceInterface    $serviceService,
        protected ServiceRepositoryInterface $serviceRepository,
        protected EndpointServiceInterface   $endpointService,
    )
    {
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function index(Request $request): Factory|View|Application
    {
        $requestData = $request->only([
            's_name',
            's_domain',
            's_server_ip',
            's_endpoint_domain',
            's_endpoint_server_ip',
        ]);

        $requestData = $this->renameKeysWithPrefix($requestData, 's_');
        $currenPage = $request->get('page', 1);
        $perPage = $request->get('number', 5);

        $customQuery = [
            'must_not' => [
                'term' => [
                    'status' => ConstantDefine::PENDING,
                ],
            ],
        ];

        $responseData = $this->serviceService->getListService($currenPage, $perPage, $requestData, $customQuery);

        $data = new LengthAwarePaginator(
            $responseData['data'],
            count($responseData['data']),
            $perPage,
            $currenPage,
            ['path' => route('services.index'), 'query' => $request->query()]
        );

        $endpoints = $this->endpointService->getListEndpoint(1, 10000);

        return view('token::services.index', ['data' => $data, 'endpoints' => $endpoints]);
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function pending(Request $request): Factory|View|Application
    {
        $requestData = $request->only([
            's_name',
            's_domain',
            's_server_ip',
            's_endpoint_domain',
            's_endpoint_server_ip',
        ]);

        $requestData = $this->renameKeysWithPrefix($requestData, 's_');
        $requestData['status'] = ConstantDefine::PENDING;
        $currenPage = $request->get('page', 1);
        $perPage = $request->get('number', 5);

        $responseData = $this->serviceService->getListService($currenPage, $perPage, $requestData);

        $data = new LengthAwarePaginator(
            $responseData['data'],
            count($responseData['data']),
            $perPage,
            $currenPage,
            ['path' => route('services.pending'), 'query' => $request->query()]
        );

        $endpoints = $this->endpointService->getListEndpoint(1, 10000);

        return view('token::services.pending', ['data' => $data, 'endpoints' => $endpoints]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function accept($id): RedirectResponse
    {
        $requestData['status'] = 10;
        $responseData = $this->serviceService->update($requestData, $id);

        if ($responseData['status'] === MessageResponseCode::MESSAGE_SUCCESS) {
            return redirect()->route('services.index')->with('success', 'Duyệt thành công!');
        }

        return redirect()->back()->with('fail', 'Duyệt thất bại!');
    }

    /**
     * @param StoreServiceRequest $request
     * @return RedirectResponse
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        // Handle input data
        $requestData = $request->validated();

        $responseData = $this->serviceService->store($requestData);

        $isSuccess = $responseData['status'] == MessageResponseCode::MESSAGE_SUCCESS;

        return redirect()->route('services.pending')->with($isSuccess ? 'success' : 'fail', $responseData['message']);
    }
}
