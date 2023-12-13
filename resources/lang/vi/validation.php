<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':Attribute phải thuộc các giá trị "yes", "on", 1, hoặc true.',
    'accepted_if' => ':Attribute phải thuộc các giá trị "yes", "on", 1, hoặc true khi :other là :value.',
    'active_url' => ':Attribute là URL không hợp lệ.',
    'after' => ':Attribute phải lớn hơn ngày :date.',
    'after_or_equal' => ':Attribute phải lớn hơn hoặc bằng :date.',
    'alpha' => ':Attribute chỉ được chứa chữ cái.',
    'alpha_dash' => ':Attribute chỉ được chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ':Attribute chỉ được chứa chữ cái và số.',
    'array' => ':Attribute phải là một array.',
    'before' => ':Attribute phải nhỏ hơn ngày :date.',
    'before_or_equal' => ':Attribute phải nhỏ hơn hoặc bằng ngày :date.',
    'between' => [
        'numeric' => ':Attribute phải nằm giữa khoảng :min và :max.',
        'file' => ':Attribute phải nằm giữa khoảng :min và :max kilobytes.',
        'string' => ':Attribute phải nằm giữa khoảng :min và :max kí tự.',
        'array' => ':Attribute phải nằm giữa khoảng :min và :max items.',
    ],
    'boolean' => ':Attribute phải là true hoặc false.',
    'confirmed' => 'Xác nhận :Attribute không khớp.',
    'current_password' => 'password không chính xác.',
    'date' => ':Attribute không đúng định dạng.',
    'date_equals' => ':Attribute phải đúng định dạng và bằng :date.',
    'date_format' => ':Attribute không khớp với định dạng :format.',
    'declined' => ':Attribute phải thuộc các giá trị "no", "off", 0, hoặc false.',
    'declined_if' => ':Attribute phải thuộc các giá trị "no", "off", 0, hoặc false khi :other là :value.',
    'different' => ':Attribute và :other phải khác nhau.',
    'digits' => ':Attribute phải có :digits chữ số.',
    'digits_between' => ':Attribute phải có từ :min đến :max chữ số.',
    'dimensions' => ':Attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => ':Attribute có một giá trị bị trùng lặp.',
    'email' => ':Attribute phải là một địa chỉ email hợp lệ.',
    'ends_with' => ':Attribute phải kết thúc bằng: :values.',
    'enum' => ':Attribute đã chọn không hợp lệ.',
    'exists' => ':Attribute đã chọn không hợp lệ.',
    'file' => ':Attribute phải là một file.',
    'filled' => ':Attribute phải có một giá trị.',
    'gt' => [
        'numeric' => ':Attribute phải lớn hơn :value.',
        'file' => ':Attribute phải lớn hơn :value kilobytes.',
        'string' => ':Attribute phải lớn hơn :value kí tự.',
        'array' => ':Attribute phải có hơn :value items.',
    ],
    'gte' => [
        'numeric' => ':Attribute phải lớn hơn hoặc bằng :value.',
        'file' => ':Attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => ':Attribute phải lớn hơn hoặc bằng :value kí tự.',
        'array' => ':Attribute phải có :value items hoặc hơn.',
    ],
    'image' => ':Attribute phải là một image.',
    'in' => ':Attribute đã chọn không hợp lệ.',
    'in_array' => ':Attribute không tồn tại trong :other.',
    'integer' => ':Attribute phải là một số nguyên.',
    'ip' => ':Attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4' => ':Attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':Attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json' => ':Attribute phải là một chuỗi JSON hợp lệ.',
    'lt' => [
        'numeric' => ':Attribute phải nhỏ hơn :value.',
        'file' => ':Attribute phải nhỏ hơn :value kilobytes.',
        'string' => ':Attribute phải nhỏ hơn :value kí tự.',
        'array' => ':Attribute phải có ít hơn :value items.',
    ],
    'lte' => [
        'numeric' => ':Attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => ':Attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => ':Attribute phải nhỏ hơn hoặc bằng :value kí tự.',
        'array' => ':Attribute không được lớn hơn :value items.',
    ],
    'mac_address' => ':Attribute phải là một địa chỉ MAC hợp lệ.',
    'max' => [
        'numeric' => ':Attribute phải nhỏ hơn :max.',
        'file' => ':Attribute phải nhỏ hơn :max kilobytes.',
        'string' => ':Attribute phải nhỏ hơn :max kí tự.',
        'array' => ':Attribute phải nhỏ hơn :max items.',
    ],
    'mimes' => ':Attribute phải là một file thuộc loại: :values.',
    'mimetypes' => ':Attribute phải là một file thuộc loại: :values.',
    'min' => [
        'numeric' => ':Attribute phải lớn hơn :min.',
        'file' => ':Attribute phải lớn hơn :min kilobytes.',
        'string' => ':Attribute phải lớn hơn :min kí tự.',
        'array' => ':Attribute phải lớn hơn :min items.',
    ],
    'multiple_of' => ':Attribute phải là bội số của :value.',
    'not_in' => ':Attribute đã chọn không hợp lệ.',
    'not_regex' => ':Attribute có định dạng không hợp lệ.',
    'numeric' => ':Attribute phải là một số.',
    'password' => 'password không chính xác.',
    'present' => ':Attribute phải tồn tại.',
    'prohibited' => ':Attribute phải empty hoặc không tồn tại.',
    'prohibited_if' => ':Attribute phải empty hoặc không tồn tại khi :other là :value.',
    'prohibited_unless' => ':Attribute phải empty hoặc không tồn tại trừ khi :other thuộc một trong các giá trị sau: :values.',
    'prohibits' => ':Attribute field prohibits :other from being present.',
    'regex' => ':Attribute không đúng định dạng.',
    'required' => ':Attribute không được để trống.',
    'required_array_keys' => ':Attribute phải chứa các mục cho :values.',
    'required_if' => ':Attribute không được để trống khi :other bằng :value.',
    'required_unless' => ':Attribute không được để trống trừ khi :other thuộc :values.',
    'required_with' => ':Attribute không được để trống khi :values có tồn tại.',
    'required_with_all' => ':Attribute không được để trống khi tất cả :values đều tồn tại.',
    'required_without' => ':Attribute không được để trống khi :values trống.',
    'required_without_all' => ':Attribute không được để trống khi tất cả :values đều không tồn tại.',
    'same' => ':Attribute và :other phải khớp với nhau.',
    'size' => [
        'numeric' => ':Attribute phải bằng :size.',
        'file' => ':Attribute phải có kích thước bằng :size kilobytes.',
        'string' => ':Attribute có độ dài là :size kí tự.',
        'array' => ':Attribute phải chứa :size items.',
    ],
    'starts_with' => ':Attribute phải bắt đầu bằng: :values.',
    'string' => ':Attribute phải là một chuỗi.',
    'timezone' => ':Attribute phải là một múi giờ hợp lệ.',
    'unique' => ':Attribute đã được sử dụng.',
    'uploaded' => ':Attribute không tải lên được.',
    'url' => ':Attribute phải là một URL hợp lệ.',
    'uuid' => ':Attribute phải là một UUID hợp lệ.',
    'g-recaptcha-response.recaptcha' => 'Captcha verification failed',
    'g-recaptcha-response.required' => 'Please complete the captcha',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'username' => 'Email hoặc số điện thoại',
        'address' => 'địa chỉ',
        'age' => 'tuổi',
        'available' => 'có sẵn',
        'body' => 'nội dung',
        'city' => 'thành phố',
        'content' => 'nội dung',
        'country' => 'quốc gia',
        'date' => 'ngày',
        'day' => 'ngày',
        'description' => 'mô tả',
        'email' => 'email',
        'excerpt' => 'trích dẫn',
        'first_name' => 'tên',
        'gender' => 'giới tính',
        'hour' => 'giờ',
        'last_name' => 'họ',
        'message' => 'lời nhắn',
        'minute' => 'phút',
        'mobile' => 'di động',
        'month' => 'tháng',
        'name' => 'tên',
        'password' => 'mật khẩu',
        'rePassword' => 'xác nhận mật khẩu',
        'password_confirmation' => 'xác nhận mật khẩu',
        'phone' => 'số điện thoại',
        'second' => 'giây',
        'sex' => 'giới tính',
        'size' => 'kích thước',
        'subject' => 'tiêu đề',
        'time' => 'thời gian',
        'title' => 'tiêu đề',
        'year' => 'năm',
        'g-recaptcha-response' => 'ReCaptcha'
    ],

];
