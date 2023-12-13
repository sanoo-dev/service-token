<?php

Route::prefix(config('laravel-sso.urlPrefix', 'sso'))->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('token', 'SSOController@token');
        Route::get('refresh-token', 'SSOController@refreshToken');
        Route::post('donate', 'SSOController@donate');
        Route::post('buy-ticket', 'SSOController@buyTicket');
        Route::post('transfer-star', 'SSOController@transferStar');
        Route::post('otp-transfer', 'SSOController@OTPTransfer');
        Route::post('member-info', 'SSOController@memberInfo');
        Route::middleware('broker-session')->group(function () {
            Route::post('receive-otp', 'SSOController@receiveOTP');
            Route::post('otp', 'SSOController@otp');
            Route::get('is-login', 'SSOController@isLogin');
            Route::post('login', 'SSOController@login');
            Route::post('register', 'SSOController@register');

            Route::post('register-by-phone', 'SSOController@registerByPhone');
            Route::post('register-by-email', 'SSOController@registerByEmail');
            Route::post('verify-phone-otp', 'SSOController@verifyPhoneOTP');
            Route::post('send-verify-email', 'SSOController@sendVerifyEmail');
            Route::post('verify-account-email', 'SSOController@verifyAccountEmail');

            Route::post('forgot-password-by-phone', 'SSOController@forgotPasswordByPhone');
            Route::post('change-password-phone', 'SSOController@changePasswordPhone');

            Route::post('forgot-password-by-email', 'SSOController@forgotPasswordByEmail');
            Route::post('check-token-password', 'SSOController@checkTokenPassword');
            Route::post('change-password-token', 'SSOController@changePasswordToken');

            Route::post('update-phone', 'SSOController@updatePhone');
            Route::post('confirm-update-phone', 'SSOController@confirmUpdatePhone');
            Route::post('update-email', 'SSOController@updateEmail');
            Route::post('confirm-update-email', 'SSOController@confirmUpdateEmail');

            Route::post('update-name', 'SSOController@updateName');
            Route::post('update-extra-info', 'SSOController@updateExtraInfo');

            Route::post('logout', 'SSOController@logout');
            Route::get('info', 'SSOController@info');
            Route::put('info', 'SSOController@updateProfile');
            Route::post('reset-password', 'SSOController@resetPassword');

            if(env('PROJECT_VERSION') == 1){
                Route::put('password', 'SSOController@updatePassword');
            }else{
                Route::put('password', 'SSOController@updatePasswordV2');
            }

            Route::put('account', 'SSOController@updateAccount');
            Route::post('receive-account-otp', 'SSOController@receiveAccountOTP');
            Route::post('verify-account-otp', 'SSOController@verifyAccountOTP');

            Route::post('social-login/{driver}', 'SocialLoginController@index')
                ->where('driver', implode('|', config('laravel-sso.socialLoginDriversAllowed')));
        });

        Route::get('social-login/callback/{driver}', 'CallbackSocialLoginController@index')
            ->where('driver', implode('|', config('laravel-sso.socialLoginDriversAllowed')));
    });
});
