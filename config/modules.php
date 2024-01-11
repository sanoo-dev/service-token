<?php
return [
    'modules' => [
        'auth' => [
            'active' => true,
            'providers' => [
                \App\Modules\Auth\Providers\AuthServiceProvider::class,
                \App\Modules\Auth\Providers\RouteServiceProvider::class,
            ],
            'modules_require'=> [

            ]
        ],
        'token' => [
            'active' => true,
            'providers' => [
                \App\Modules\Token\Providers\TokenServiceProvider::class,
                \App\Modules\Token\Providers\RouteServiceProvider::class,
            ],
            'modules_require'=> [
                'auth'
            ]
        ],
    ]
];
