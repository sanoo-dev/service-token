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

    'success' => 'Thành công.',
    'paramsInvalid' => 'Tham số không hợp lệ.',
    'authorizationInvalid' => 'Thiếu authorization.',
    'broker' => [
        'notExist' => 'Broker không tồn tại.',
        'cannotAccess' => 'Broker không được phép truy cập.',
    ],
    'token' => [
        'getSuccess' => 'Nhận token thành công.',
        'getFailed' => 'Nhận token thất bại.',
        'invalid' => 'Thiếu token hoặc SecretKey không hợp lệ.',
        'oldTokenInvalid' => 'Token cũ không có sẵn hoặc không hợp lệ.',
        'expired' => 'Token đã hết hạn.',
        'refresh' => [
            'getSuccess' => 'Làm mới token thành công.',
            'getFailed' => 'Làm mới token thất bại.',
            'expired' => 'Token cũ không thể làm mới.',
        ]
    ],
    'auth' => [
        'otp' => [
            'sendSuccess' => 'Gửi OTP thành công.',
            'sendFailed' => 'Gửi OTP thất bại.',
            'loginSuccess' => 'Đăng nhập bằng OTP thành công.',
            'loginFailed' => 'Đăng nhập bằng OTP thất bại.',
            'verifySuccess' => 'Xác thực OTP thành công.',
            'verifyFailed' => 'Xác thực OTP thất bại.'
        ],
        'verifyAccountSuccess' => 'Xác thực tài khoản thành công',
        'verifyAccountFailed' => 'Xác thực tài khoản thất bại',
        'getLinkSuccess' => 'Gửi link thành công.',
        'getLinkFailed' => 'Gửi link thất bại',
        'checkTokenSuccess' => 'Token tồn tại',
        'checkTokenFailed' => 'Token không tồn tại',
        'notFound' => 'Thành viên không tồn tại.',
        'notLogin' => 'Thành viên chưa đăng nhập.',
        'loginSuccess' => 'Đăng nhập thành công.',
        'loginFailed' => 'Đăng nhập thất bại.',
        'logoutSuccess' => 'Đăng xuất thành công.',
        'logoutFailed' => 'Đăng xuất thất bại.',
        'registerSuccess' => 'Đăng kí thành công.',
        'registerFailed' => 'Đăng kí thất bại.',
        'resetPasswordSuccess' => 'Đặt lại mật khẩu thành công.',
        'resetPasswordFailed' => 'Đặt lại mật khẩu thất bại.',
        'getInfoSuccess' => 'Nhận thông tin thành viên thành công.',
        'getInfoFailed' => 'Nhận thông tin thành viên thất bại.',
        'update' => [
            'accountSuccess' => 'Cập nhật thành viên thành công.',
            'accountFailed' => 'Cập nhật thành viên thất bại.',
            'passwordSuccess' => 'Cập nhật mật khẩu thành công.',
            'passwordFailed' => 'Cập nhật mật khẩu thất bại.',
            'profileSuccess' => 'Cập nhật thông tin thành viên thành công.',
            'profileFailed' => 'Cập nhật thông tin thành viên thất bại.',
        ]
    ],
    'otherError' => 'Đã xảy ra lỗi.',
    'donate'=>[
        'donateSuccess' => 'Donate thành công.',
        'donateFailed' => 'Donate thất bại.'
    ],
    'transfer'=>[
        'transferSuccess' => 'Chuyển thành công.',
        'transferFailed' => 'Chuyển thất bại.'
    ]


];
