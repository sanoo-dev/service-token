<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'success' => 'Success',
    'paramsInvalid' => 'Params does invalid.',
    'authorizationInvalid' => 'Authorization is missing.',
    'broker' => [
        'notExist' => 'Broker does not exist.',
        'cannotAccess' => 'Broker is not allowed to access.',
    ],
    'token' => [
        'getSuccess' => 'Get token success',
        'getFailed' => 'Get token failed',
        'invalid' => 'Token does not exist or secret key not compatible',
        'oldTokenInvalid' => 'Old token does not exist or invalid',
        'expired' => 'Token has expired',
        'refresh' => [
            'getSuccess' => 'Refresh token success',
            'getFailed' => 'Refresh token failed',
            'expired' => 'Old token can\'t refresh',
        ]
    ],
    'auth' => [
        'otp' => [
            'sendSuccess' => 'Send OTP success',
            'sendFailed' => 'Send OTP failed',
            'loginSuccess' => 'Login by OTP success',
            'loginFailed' => 'Login by OTP failed',
            'verifySuccess' => 'Verify OTP success',
            'verifyFailed' => 'Verify OTP failed'
        ],
        'notFound' => 'Member not found',
        'notLogin' => 'Member not login',
        'loginSuccess' => 'Login success',
        'loginFailed' => 'Login failed',
        'logoutSuccess' => 'Logout success',
        'logoutFailed' => 'Logout failed',
        'registerSuccess' => 'Register success',
        'registerFailed' => 'Register failed',
        'resetPasswordSuccess' => 'Reset password success',
        'resetPasswordFailed' => 'Reset password failed',
        'getInfoSuccess' => 'Get info success',
        'getInfoFailed' => 'Get info failed',
        'update' => [
            'accountSuccess' => 'Update account success',
            'accountFailed' => 'Update account failed',
            'passwordSuccess' => 'Update password success',
            'passwordFailed' => 'Update password failed',
            'profileSuccess' => 'Update profile success',
            'profileFailed' => 'Update profile failed',
        ]
    ],
    'otherError' => 'Something went wrong'

];
