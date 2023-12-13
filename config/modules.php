<?php
return [
    'modules' => [
        'auth' => [
            'active' => true,
            'providers' => [
                \App\Modules\Auth\Providers\AuthServiceProvider::class
            ],
            'modules_require'=> [

            ]
        ],
        'token' => [
            'active' => true,
            'providers' => [
                \App\Modules\Token\Providers\TokenServiceProvider::class
            ],
            'modules_require'=> [
                        'auth'
            ]
        ],
    ]
];
