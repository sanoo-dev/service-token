<?php
return [
    'cookieName' => env('SSO_BROKER_COOKIE_NAME', 'sso'),
    'cookieExpiredTime' => env('SSO_BROKER_COOKIE_EXPIRED_TIME', 5), //5 minutes
    'cookieDomain' => env('SSO_BROKER_COOKIE_DOMAIN', '.tuoitre.vn'),
    'serverUrl' => env('SSO_SERVER_URL', 'http://sso-member.tuoitre.local/sso/'),
    'brokerName' => env('SSO_BROKER_NAME', 'sso_broker'),
    'brokerSecretKey' => env('SSO_BROKER_SECRET_KEY', ''),
    'brokerPublicKey' => env('SSO_BROKER_PUBLIC_KEY', ''),
    'sessionInfo'=> env('SSO_SESSION_INFO', 'sso_info')
];
