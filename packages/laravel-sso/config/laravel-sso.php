<?php

return [
    'version' => env('SSO_API_VERSION', 'v1'),
    'urlPrefix' => env('SSO_URL_PREFIX', 'sso'),
    'middleware' => ['sso-api'],
    'maxAttemptsPerMinutes' => env('SSO_MAX_ATTEMPTS_PER_MINUTE', 150),
    'defaultExpiredMinutes' => env('SSO_BROKER_DEFAULT_SESSION_EXPIRED_MINUTES', 60),
    // default 1h
    'defaultRememberLoginExpiredMinutes' => env('SSO_BROKER_DEFAULT_SESSION_REMEMBER_LOGIN_EXPIRED_MINUTES', 43800),
    // default 1 month
    'defaultKeepTokenAfterExpiredMinutes' => env('SSO_BROKER_DEFAULT_KEEP_TOKEN_AFTER_EXPIRED_MINUTES', 21600),
    // default 15 days
    'notUseDatabaseForApi' => true,
    'redisConnection' => 'default',
    'redisPrefix' => 'brokers_',
    'primaryAttribute' => 'name',
    'memberServerUrl' => env('MEMBER_SERVER_URL', 'http://api-member.tuoitre.local/'),
    'sessionName' => env('SSO_SESSION_NAME', '_sso_id'),
    'cacheExpiredMinutes' => env('SSO_CACHE_EXPIRED_MINUTES', 1),
    'socialLoginDriversAllowed' => [
        'google',
        'facebook'
    ],
];
