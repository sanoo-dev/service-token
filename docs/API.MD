# API DOCS

## Client
**CLIENT** được cấp các thông tin:

- broker_name: Tên (ex: beta-client)

- broker_public_key: Public key (ex: oQt5MjeoVNokTWVmdUu0bUPNd7HPDkXrtCtChMBnqLVdS8ck)

- broker_secret_key: Secret key (ex: A46MhQOxEHOfQy7BkuqpVB1ZGRcRtWXgD7EmV04QsfOzJZ3sCY066vbUJGOfneqG)

## Host:
Dev Environment: https://beta-sso.tuoitre.vn/sso/v1/

Production Environment: https://sso.tuoitre.vn/sso/v1/

## Api:

### Headers

```
    {
        "Authorization": "Bearer eyJ***.eyJ0b***.eC9lt***",
        "Accept": "application/json",
        ...
    }
```

- Token: được lấy api (1)

=> Header được sử dụng cho các api yêu cầu header


### Lưu ý response:
Khi HTTP Status Code lớn hơn 200 (lỗi) và response có chứa các attr sau thì cần refresh hoặc new mới token:


Đối với response chứa "require_new": true:
```
{
    "message": "...",
    ...
    "require_new": true
}
```
=> call api */token để lấy token mới

Đối với response chứa "require_refresh": true
```
{
    "message": "...",
    ...
    "require_refresh": true
}
```
=> call api */refresh-token để refresh token cũ sang token mới

### List

1. #### Get Token:

   - Description: Sử dụng để lấy request token
   - Url: /token
   - Method: GET
   - Params:
     - broker (required): broker_name (được cung cấp)
     - publicKey (required): broker_public_key (được cung cấp)
   - Response:
     - Thành công:
       - Status code: 200
       - Json example:
       ```
       {
           "message": "Nhận token thành công.",
           "token": "eyJ***.eyJpc3M***.z4vrUJ3Ns7eJQ9_-wYFJIi4Fgja-8fVaIcBcENjvHgw",
           "code": 200
       }
       ```
     - Thất bại:
       - Status code: 400/401
       - Json example:
       ```
        {
            "message": "Nhận token thất bại."/"Broker không tồn tại.",
            "type": 100/3
            "code": 400/401
        }
       ```

    Note: token là dạng JWT Token, cần được decode bằng secret_key (được cung cấp)

    Sau khi decode JWT, ta được phần payload có như ví dụ:

    ```
    {
        "iat": 1655716796,
        "token": "5bb658e51922750d824448070dec811be645743e353de70e73360d396737ce54",
        "broker": "beta-client",
        ...
    }
    ```
    Trong payload có attribute "token", ta lưu xuống cookie với tên và domain như ví dụ bên dưới:
    ```
    <script type="text/javascript">
        var cookieName = '_sid';
        var cookieValue = token;
        var cookieDomain = '.tuoitre.vn';
        var myDate = new Date();
        myDate.setMinutes(myDate.getMinutes() + 15);
        document.cookie = cookieName +"=" + cookieValue + ";expires=" + myDate + ";domain=" + cookieDomain + ";path=/";
    </script>
    ```

    Nếu đã có sẵn token từ cookie, ta không cần gọi api này để lấy token mới nữa.

    Để tiến hành sử dụng các chức năng từ SSO, ta cần encode token thành 1 JWT theo như ví dụ dưới:
    ```
       header: {"typ": "JWT","alg": "HS256"}
       payload: {"token":token,"broker":broker_name,"iat":....}
       signature key: broker_secret_key
    ```

    Ex:
    ```
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jsrsasign/8.0.20/jsrsasign-all-min.js"></script>
        <script>
            // Header
            var header = {alg: 'HS256', typ: 'JWT'};
            // Payload
            var payload = {
                token: "token_from_cookie",
                broker: "broker_name",
                iat:KJUR.jws.IntDate.get('now')
            };
            var signatureKey = "broker_secret_key"
            // Sign JWT, password=616161
            var sHeader = JSON.stringify(header);
            var sPayload = JSON.stringify(payload);
            var sJWT = KJUR.jws.JWS.sign("HS256", sHeader, sPayload, "616161");

            console.log(sJWT); //eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6InRva2VuX2Zyb21fY29va2llIiwiYnJva2VyIjoiYnJva2VyX25hbWUiLCJpYXQiOjE2NTU3MTg1NzF9.eC9lts08mEfSFktUAUkbANH88ilOguui29WM2Vorm8k
        </script>
    ```

    Gắn jwt này trên header để xác thực cho các request tới các chức năng của SSO như ví dụ dưới:
    ```
    {
        "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6InRva2VuX2Zyb21fY29va2llIiwiYnJva2VyIjoiYnJva2VyX25hbWUiLCJpYXQiOjE2NTU3MTg1NzF9.eC9lts08mEfSFktUAUkbANH88ilOguui29WM2Vorm8k",
        "Accept": "application/json",
        ...
    }
    ```

2. #### Refresh token:

    - Description: Sử dụng để refresh token
    - Url: /refresh-token
    - Header: (required)
    - Method: GET
    - Params:
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
           "message": "Làm mới token thành công.",
           "token": "605853eb80bb0553ffc2c4884ab7ada25f5962a6ef042bdbb350eea85370d90e",
           "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json example:
        ```
        {
           "message": "Làm mới token thành công."/"Broker không tồn tại."/"Broker không được phép truy cập."/"Token cũ không có sẵn hoặc không hợp lệ."/"Token cũ không thể làm mới."/...,
           "type" : 3/10/12/100/...
           "code": 400/401
        }
        ```
    **Note: Đối với trường hợp khi refresh thành công, API sẽ trả về 1 token mới, bạn cần update lại cookie với token mới này.**

3. #### Login (by username (email or phone) and password):

    - Description: Sử dụng để login vào hệ thống
    - Url: /login
    - Header: Như trên
    - Method: POST
    - Params:
      - username (required): email hoặc số đt
      - password (required): mật khẩu
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Đăng nhập thành công.",
            "data": {
                "token": "686c885fc4a3baedd61dac7fde40dbd6ca8e767383a5a4b3bb9005a1a7058f06",
                "info": {
                    "id": "97539c0a06234c07a8a6751b037637f5",
                    "email": null,
                    "phone": "0797113505",
                    "name": "",
                    "gender": "0",
                    "birth_timestamp": "",
                    "avatar_path": null,
                    "avatar_base_url": null,
                    "address": "",
                    "website": "",
                    "type_account": 1,
                    "company_name": null,
                    "company_certificate": null
                }
            },
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json example:
        ```
        {
            "message": "Đăng nhập thất bại.",
            "errors": {
                "username": "Thông tin tài khoản không đúng"
            },
            "type": 21,
            "code": 400
        }
        ```

    **Note: Đối với trường hợp khi login thành công, API sẽ trả về 1 token mới, bạn cần update lại cookie với token mới này.**

4. #### Receive OTP:

    - Description: Sử dụng để lấy mã OTP
    - Header: (required)
    - Url: /receive-otp
    - Method: POST
    - Params:
      - phone (required): Số điện thoại
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Gửi OTP thành công",
            "data": {
                "phone": "0969113501"
            },
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json example:
        ```
        {
        "message": "Gửi OTP thất bại.",
            "errors": {
                "other": "..."
            },
            "type": 20
            "code": 400
        }
        ```

5. #### Login or register by OTP:

    - Description: Sử dụng để đăng nhập hoặc đăng kí bằng mã OTP
    - Header: (required)
    - Url: /otp
    - Method: POST
    - Params:
      - phone (required): Số điện thoại
      - otp (required): Mã otp
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Đăng nhập bằng OTP thành công.",
            "data": {
                "token": "3574d3ac163b3e87c37d79bae18ff52a7efb5a71e6dfbda86eaeea2476c9ebfc",
                "info": {
                    "id": "5e40b9cf9acbd",
                    "email": "quocdai271096@gmail.com",
                    "phone": "0969113505",
                    "name": null,
                    "gender": null,
                    "birth_timestamp": null,
                    "avatar_path": null,
                    "avatar_base_url": null,
                    "address": null,
                    "website": null,
                    "type_account": null,
                    "company_name": null,
                    "company_certificate": null
                }
            },
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json example:
        ```
        {
            "message": "Đăng nhập bằng OTP thất bại.",
            "errors": {
                "otp": "Thông tin tài khoản không đúng"
            },
            "type": 21,
            "code": 400
        }
        ```

6. #### Get info:

    - PreCondition: Đã login
    - Description: Sử dụng để lấy thông tin member
    - Header: (required)
    - Url: /info
    - Method: GET
    - Params:
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Nhận thông tin thành viên thành công.",
            "data": {
                "id": "5e40b9cf9acbd",
                "email": "quocdai271096@gmail.com",
                "phone": "0969113505",
                "name": null,
                "gender": null,
                "birth_timestamp": null,
                "avatar_path": null,
                "avatar_base_url": null,
                "address": null,
                "website": null,
                "type_account": null,
                "company_name": null,
                "company_certificate": null
            },
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401/404
        - Json example:
        ```
        {
            "message": "Thành viên không tồn tại.",
            "errors": [],
            "type": 24,
            "code": 404
        }
        ```

7. #### Logout:

   - PreCondition: Đã login
   - Description: Sử dụng việc member đăng xuất
   - Header: (required)
   - Url: /logout
   - Method: POST
   - Params:
   - Response:
     - Thành công:
       - Status code: 200
       - Json example:
       ```
        {
            "message": "Đăng xuất thành công.",
            "token": "88d5c50eb89214cee08a3a0a5c86a032799104ad530b3b8ba6ff02a6dde33587",
            "code": 200
        }
       ```
   - Thất bại:
     - Status code: 400/401
     - Json example:
     ```
      {
          "message": "Đăng xuất thất bại.",
          "type":26
          "code": 400
      }
     ```
     **Note: Đối với trường hợp khi logout thành công, API sẽ trả về 1 token mới, bạn cần update lại cookie với token mới này.**

8. #### Register:

    - Description: Sử dụng để đăng kí mới
    - Header: (required)
    - Url: /register
    - Method: POST
    - Params:
      - email (required if not phone): email
      - phone (required if not email): số đt
      - password (required): mật khẩu
      - rePassword (required): nhập lại mật khẩu
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Đăng kí thành công.",
            "data": [],
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json example:
        ```
        {
            "message": "Đăng kí thất bại.",
            "errors": {
                "other": "Số điện thoại đã tồn tại"
            },
            "type": 22,
            "code": 400
        }
        ```

9. #### Check login:

    - Description: Sử dụng để kiểm tra đăng nhập
    - Header: (required)
    - Url: /is-login
    - Method: GET
    - Params:
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "login": false,
            "code": 200
        }
         ```
      - Thất bại:
        - Status code: 200
        - Json example:
        ```
        {
            "login": false,
            "code": 200
        }
        ```

10. #### Reset Password:

    - Description: Sử dụng để reset password của member
    - Header: (required)
    - Url: /reset-password
    - Method: POST
    - Params:
        - emailOrPhone (required): Email hoặc Số đt
    - Response:
        - Thành công:
            - Status code: 200
            - Json example:
          ```
          {
              "message": "Đặt lại mật khẩu thành công.",
              "data": [],
              "code": 200
          }
          ```
        - Thất bại:
            - Status code: 400
            - Json example:
          ```
          {
              "message": "Đặt lại mật khẩu thất bại.",
              "errors": {
                  "other": "Số điện thoại chưa được đăng ký"
              },
              "type": 25,
              "code": 400
          }
          ```

11. #### Update Profile:

    - PreCondition: Đã login
    - Description: Sử dụng để cập nhật profile member
    - Header: (required)
    - Url: /info
    - Method: PUT
    - Params:
      - name (option): Tên member
      - gender (option): Giới tính (1->Nam, 2->Nữ, 3->Khác)
      - address (option): Địa chỉ
      - birthday (option): Ngày sinh (timestamps)
      - website (option): Url website cá nhân/tổ chức
      - typeAccount (option): Loại account
      - companyName (option): Tên công ty
      - companyCertificate (option): ...
      - avatarBaseUrl (option): ...
      - avatarPath (option): ...
    - Response:
      - Thành công:
        - Status code: 200
        - Json example:
        ```
        {
            "message": "Cập nhật thông tin thành viên thành công.",
            "data": [],
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json:
        ```
        {
            "message": "Cập nhật thông tin thành viên thất bại.",
            "errors": [
                "other": "..."
            ],
            "type": 22
            "code": 400
        }
        ```

12. #### Receive Account OTP:

    - PreCondition: Đã login
    - Description: Sử dụng để lấy otp để xác thực chính chủ cho tài khoản
    (được sử dụng để cập nhật các thông tin tài khoản quan trọng như email, số đt)
    - Header: (required)
    - Url: /receive-account-otp
    - Method: POST
    - Params:
        - type (required) ("phone" hoặc "email"): Loại xác thực (Xác thực qua "email" hoặc "phone")
    - Response:
        - Thành công:
            - Status code: 200
            - Json:
          ```
            {
                "message": "Gửi OTP thành công.",
                "data": [],
                "code": 200
            }
          ```
        - Thất bại:
            - Status code: 400/401
            - Json:

          ```
            {
                "message": "Gửi OTP thất bại.",
                "errors": {
                    "other": "Gửi OTP thất bại."
                },
                "code": 400
            }
          ```

13. #### Verify Account OTP:

    - PreCondition: Đã login
    - Description: Sử dụng để kiểm tra OTP nhận được từ API trên (12) đúng hay sai.
      - Nếu đúng: Trả về một `tokenForUpdate` được sử dụng để cập nhật các thông tin tài khoản
    - Header: (required)
    - Url: /verify-account-otp
    - Method: POST
    - Params:
      - type (required) ("phone" hoặc "email"): Loại xác thực (Xác thực qua "email" hoặc "phone")
      - otp (required): Mã OTP
    - Response:
      - Thành công:
        - Status code: 200
        - Json:
        ```
        {
            "message": "Xác thực OTP thành công.",
            "data": {
                "tokenForUpdate": "s05bLi0UfGdqLZ5thHFz8tV2DSY5jpwh"
            },
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json:
        ```
        {
            "message": "Xác thực OTP thất bại.",
            "errors": {
                "other": "Thông tin xác nhận không đúng"
            },
            "type": 22,
            "code": 400
        }
        ```

14. #### Update Account: (Updating)

    - PreCondition: Đã login
    - Description: Sử dụng để cập nhật các thông tin chính của tài khoản
    - Header: (required)
    - Url: /account
    - Method: PUT
    - Params:
      - email (required if not phone): Email
      - phone (required if not email): Số đt
      - otp: Mã OTP nhận được từ api (4)
      - tokenForUpdate: token nhận được từ api (13)
    - Response:
      - Thành công:
        - Status code: 200
        - Json:
        ```
        {
            "message": "Cập nhật thành viên thành công.",
            "data": [],
            "code": 200
        }
        ```
      - Thất bại:
        - Status code: 400/401
        - Json:
        ```
        {
            "success": false,
            "message": "Cập nhật thành viên thất bại.",
            "errors": {
                "other": "Cập nhật thành viên thất bại."
            },
            "type": 22,
            "code": 400
        }
        ```

    **Note:**
    - Các bước để cập nhật email hoặc password:
      - b1: Call api (12) để nhận otp xác thực chính chủ đang thực hiện hành động
      - b2: Call api (13) để xác thực otp trên đúng hay sai
        - Nếu đúng: được nhận một token (`tokenForUpdate`) để thực hiện bước sau
      - b3: Nhập email mới (hoặc số điện thoại mới)
      - b4: Call api (4) để nhận otp xác thực email mới (hoặc số đt mới) có tồn tại
      - b5: Call api (15) để cập nhật email mới (hoặc số đt mới) với các params:
        - email hoặc số đt
        - tokenForUpdate (nhận được ở b2)
        - otp (nhận được ở b4)

15. #### Update Pasword:  (Updating)

    - PreCondition: Đã login
    - Description: Sử dụng để cập nhật password của member
    - Header: (required)
    - Url: /password
    - Method: PUT
    - Params:
      - oldPassword (required): Mật khẩu cũ
      - newPassword (required): Mật khẩu mới
      - reNewPassword (required): Nhập lại mật khẩu mới
    - Response:
      - Thành công:
        - Status code: 200
        - Json:
        ```
        ```
      - Thất bại:
        - Status code: 400/401
        - Json:
        ```
        ```
