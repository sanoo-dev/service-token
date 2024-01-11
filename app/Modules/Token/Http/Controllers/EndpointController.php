<?php

namespace App\Modules\Token\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Token\Helpers\Constants\MessageResponseCode;
use App\Modules\Token\Http\Requests\StoreEndpointRequest;
use App\Modules\Token\Repositories\Elasticsearch\Interfaces\EndpointRepositoryInterface;
use App\Modules\Token\Services\Interfaces\EndpointServiceInterface;
use App\Modules\Token\Traits\KeyRenaming;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class EndpointController extends Controller
{
    use KeyRenaming;

    /**
     * @param EndpointServiceInterface $endpointService
     * @param EndpointRepositoryInterface $endpointRepository
     */
    public function __construct(
        protected EndpointServiceInterface    $endpointService,
        protected EndpointRepositoryInterface $endpointRepository,
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
        ]);

        $requestData = $this->renameKeysWithPrefix($requestData, 's_');

        $currenPage = $request->get('page', 1);
        $perPage = $request->get('number', 5);

        $responseData = $this->endpointService->getListEndpoint($currenPage, $perPage, $requestData);

        $data = new LengthAwarePaginator(
            $responseData['data'],
            count($responseData['data']),
            $perPage,
            $currenPage,
            ['path' => route('endpoints.index'), 'query' => $request->query()]
        );

        return view('token::endpoints.index', ['data' => $data]);
    }

    /**
     * @param StoreEndpointRequest $request
     * @return RedirectResponse
     */
    public function store(StoreEndpointRequest $request): RedirectResponse
    {
        // Handle input data
        $requestData = $request->validated();

        $responseData = $this->endpointService->store($requestData);

        $isSuccess = $responseData['status'] == MessageResponseCode::MESSAGE_SUCCESS;

        return redirect()->back()->with($isSuccess ? 'success' : 'fail', $responseData['message']);
    }
}
