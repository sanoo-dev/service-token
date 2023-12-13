<?php

namespace app\Modules\Token\Http\Controllers;

use App\Http\Controllers\Controller;


use App\Modules\Token\Services\ApiTokenService;

use App\Modules\Token\Helpers\Constants\ConstantDefine;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;


class ServiceFeController extends Controller
{
    public function __construct(
        protected ApiTokenService $serviceController,
    )

    {

    }

    public function viewLogin(): Factory|View|Application
    {
        return view('token::login');
    }
    public function viewWelcome(): Factory|View|Application
    {
        return view('token::layouts.page_welcome');
    }

    public function viewManageService(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {
        $data['domainTransfer'] = $request->get('domainTransfer') ?? null;
        $data['serveTransfer'] = $request->get('serveIpTransfer') ?? null;
        $endpoint = $tokenService->getListAllEndPoint([]);
        $data['page'] = $request->get('page') ?? 1;
        $data['number'] = $request->get('number') ?? 10;
//        $temp=$tokenService->getListAllEndPoint($data);



        $name = $request->get('nameSearch') ?? null;
        $domain = $request->get('domainSearch') ?? null;
        $ip = $request->get('ipSearch') ?? null;
        $domainEnd = $request->get('domainEndSearch') ?? null;
        $ipEnd = $request->get('ipEndSearch') ?? null;

        $search = [];
        if (!empty($data['nameSearch'])) {
            $search[] = [
                'match' => ['appName' => $name],
            ];

        }
        if (!empty($name)) {
            $search[] = [
                'match' => ['appName' => $name],
            ];
        }
        if (!empty($domain)) {
            $search[] = [
                'match' => ['domain' => $domain],
            ];
        }
        if (!empty($ip)) {
            $search[] = [
                'match' => ['serveIp' => $ip],
            ];
        }
        if (!empty($domainEnd)) {
            $search[] = [
                'match' => ['domainTransfer' => $domainEnd],
            ];
        }
        if (!empty($ipEnd)) {
            $search[] = [
                'match' => ['serveIpTransfer' => $ipEnd],
            ];
        }


                $not[] = [
                    'match' => ['status' => ConstantDefine::PENDING],
                    ];

        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
        $params = [
            'index' => 'token_service',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>
                            $search,
                         'must_not' =>
                             $not
                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];

        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });
        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewManageService'), 'query' => $request->query()] // Additional options
        );

        return view('token::services.index', ['data' => $paginator, 'endpoint' => $endpoint]);

    }



    public function viewAcceptService(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {
        $data['page'] = $request->get('page') ?? 1;
        $data['number'] = $request->get('number') ?? 10;


        $name = $request->get('nameSearch') ?? null;
        $domain = $request->get('domainSearch') ?? null;
        $ip = $request->get('ipSearch') ?? null;
        $domainEnd = $request->get('domainEndSearch') ?? null;
        $ipEnd = $request->get('ipEndSearch') ?? null;

        $search = [];
        $search[] = [
            'match' => ['status' => ConstantDefine::PENDING],
        ];
        if (!empty($data['nameSearch'])) {
            $search[] = [
                'match' => ['appName' => $name],
            ];

        }
        if (!empty($name)) {
            $search[] = [
                'match' => ['appName' => $name],
            ];
        }
        if (!empty($domain)) {
            $search[] = [
                'match' => ['domain' => $domain],
            ];
        }
        if (!empty($ip)) {
            $search[] = [
                'match' => ['serveIp' => $ip],
            ];
        }
        if (!empty($domainEnd)) {
            $search[] = [
                'match' => ['domainTransfer' => $domainEnd],
            ];
        }
        if (!empty($ipEnd)) {
            $search[] = [
                'match' => ['serveIpTransfer' => $ipEnd],
            ];
        }

        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
//        $client = ClientBuilder::create()->build();
        $params = [
            'index' => 'token_service',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>
                            $search

                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];


        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewAcceptService'), 'query' => $request->query()] // Additional options
        );

        $endpoint = $tokenService->getListEndPoint([]);

        return view('token::services.accept_service', ['data' => $paginator, 'endpoint' => $endpoint]);

    }

    public function createPaddingService(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {
        $data['page'] = $request->get('page') ?? 1;
        $data['number'] = $request->get('number') ?? 10;
        $data['appId'] = !empty($request->post('appId')) ? $request->post('appId') : null;
        $data['name'] = !empty($request->post('name')) ? $request->post('name') : null;
        $data['serveIp'] = !empty($request->post('serveIp')) ? $request->post('serveIp') : null;
        $data['domain'] = !empty($request->post('domain')) ? $request->post('domain') : null;
        $data['serveIpTransfer'] = !empty($request->post('serveIpTransfer')) ? $request->post('serveIpTransfer') : null;
        $data['domainTransfer'] = !empty($request->post('domainTransfer')) ? $request->post('domainTransfer') : null;
        $check = $tokenService->saveInfoService($data);
        $code = $check['code'] ?? null;
        $message = $check['message'] ?? null;
        $endpoint = $tokenService->getListAllEndPoint([]);
        $search[] = [
            'match' => ['status' => ConstantDefine::PENDING],
        ];
        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
        $params = [
            'index' => 'token_service',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>
                            $search
                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];
        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });
        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewAcceptService'), 'query' => $request->query()] // Additional options
        );
        $data = !empty($empty) ? $empty : [];
        return view('token::services.accept_service', ['code' => $code, 'message' => $message, 'data' => $paginator, 'endpoint' => $endpoint]);


    }

    public function acceptPaddingService(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {

        $data = $request->post();
        $data['page'] = $request->get('page') ?? 1;
        $data['number'] = $request->get('number') ?? 10;
        $check = $tokenService->acceptService($data);
        $code = $check['code'] ?? null;
        $message = $check['message'] ?? null;
        $endpoint = $tokenService->getListAllEndPoint([]);

        $search[] = [
            'match' => ['status' => ConstantDefine::PENDING],
        ];
        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
        $params = [
            'index' => 'token_service',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>
                            $search

                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];

        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });
        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewAcceptService'), 'query' => $request->query()] // Additional options
        );

        return view('token::services.accept_service', ['code' => $code, 'message' => $message, 'data' => $paginator, 'endpoint' => $endpoint]);



    }



    public function viewManageEndPoint(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {
        $data['page'] = $request->get('page') ?? 1;

        $data['number'] = $request->get('number') ?? 5;
//        $temp=$tokenService->getListAllEndPoint($data);
        $name = $request->get('nameEnd') ?? null;
        $domain = $request->get('domainEnd') ?? null;
        $ip = $request->get('ipEnd') ?? null;

        $search = [];

        if (!empty($name)) {
            $search[] = [
                'match' => ['name' => $name],
            ];
        }
        if (!empty($domain)) {
            $search[] = [
                'match' => ['domain' => $domain],
            ];
        }
        if (!empty($ip)) {
            $search[] = [
                'match' => ['serveIp' => $ip],
            ];
        }



        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
        $params = [
            'index' => 'token_end_points',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>
                            $search

                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];

        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewManageEndPoint'), 'query' => $request->query()] // Additional options
        );

        return view('token::endpoint.index', ['data' => $paginator]);
    }


    function paginate($items, $perPage)
    {
        $pageStart = request('page', 1);
        $offSet = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, TRUE);

        return new LengthAwarePaginator(
            $itemsForCurrentPage, count($items), $perPage,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function viewManageUser(): Factory|View|Application
    {

        return view('token::user.index');
    }

    public function viewCreateUser(): Factory|View|Application
    {

        return view('token::create.user');
    }

    public function viewCreateService(): Factory|View|Application
    {

        return view('token::create.service');
    }

    public function viewCreateEndPoint(): Factory|View|Application
    {

        return view('token::create.endpoint');
    }

    public function createEndPoint(ApiTokenService $tokenService, Request $request): Factory|View|Application
    {

        $data['name'] = !empty($request->post('name_endpoint')) ? $request->post('name_endpoint') : null;
        $data['serveIp'] = !empty($request->post('serveip')) ? $request->post('serveip') : null;
        $data['domain'] = !empty($request->post('domain')) ? $request->post('domain') : null;
        $data['expire'] = !empty($request->post('expire')) ? $request->post('expire') : null;

        $data['page'] = $request->get('page') ?? 1;
        $data['number'] = $request->get('number') ?? 5;

        $check = $tokenService->saveInfoTransfer($data);
        $code = $check['code'] ?? null;
        $message = $check['message'] ?? null;

        $client=ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST').':9200'])->build();
        $params = [
            'index' => 'token_end_points',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [

                        ],
                    ],
                ],
                'from' => ($data['page'] - 1) * $data['number'], // Calculate the starting point for the current page
                'size' => $data['number'], // Number of items per page
            ],
        ];

        // Execute the search query
        $response = $client->search($params);

        // Get the total number of items from the Elasticsearch response
        $total = $response['hits']['total']['value'];

        // Extract the search results from the Elasticsearch response
        $hits = $response['hits']['hits'];

        // Transform the search results into a Collection
        $results = collect($hits)->map(function ($hit) {
            return $hit['_source'];
        });

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $results, // Paginated items
            $total, // Total number of items
            $data['number'], // Number of items per page
            $data['page'], // Current page
            ['path' => route('viewManageEndPoint'), 'query' => $request->query()] // Additional options
        );

        return view('token::endpoint.index', ['code' => $code, 'message' => $message, 'data' => $paginator]);


    }

    public function viewDetailEndPoint(): Factory|View|Application
    {

        return view('token::detail.endpoint');
    }

    public function viewDetailService(): Factory|View|Application
    {

        return view('token::detail.services');
    }

    public function viewDetailUser(): Factory|View|Application
    {

        return view('token::user.detail');
    }

}
