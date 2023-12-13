# FRONT API

## Requirement

Only used for sites with the same parent domain. (`.tuoitre.vn`)

## Host:

Dev Environment: https://beta-sso.tuoitre.vn/api/front/v1/

Production Environment: https://sso.tuoitre.vn/api/front/v1/

## Workflow:

User => Access site xyz.tuoitre.vn
=> site xyz.tuoitre.vn check login by calling api info `2`

=> If already logged in, api `2` return info member
and show button `logout` is used to call api logout `3`
=> When click button `logout` => call api logout `3` => Return result is success or failed

=> If not login, show button `login` is used to call api login `1`
=> When click button `login` => call api login `1` => redirect to site SSO (sso.tuoitre.vn)
=> Login on site SSO => After success => Redirect to url in param `redirectUrl`

## Api:

1. #### Login:

    - Description: Sử dụng redirect đến trang login trên site SSO
    - Url: /auth/login
    - Method: GET
    - Params:
        - redirectUrl (required): url redirect sau khi login thành công trên site SSO
    - Ex:
      ```
        axios({
            method: 'GET',
            url: ' https://beta-sso.tuoitre.vn/api/front/v1/auth/login?redirectUrl=https://tuoitre.vn/test',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            window.open(response.data?.loginUrl ?? null, '_self')
            console.log(response)
        }).catch((error) => {
            console.log(error)
        });
      ```    

2. #### Check Login:

    - Description: Sử dụng kiểm tra login
    - Url: /auth/login/check
    - Method: GET
    - Ex:
      ```
        axios({
            method: 'GET',
            url: ' https://beta-sso.tuoitre.vn/api/front/v1/auth/login/check',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            window.open(response.data?.loginUrl ?? null, '_self')
            console.log(response)
        }).catch((error) => {
            console.log(error)
        });
      ```    

3. #### Info:

    - Description: Sử dụng redirect đến trang login trên site SSO
    - Url: /auth/info
    - Required: Share Cookie
    - Method: GET
    - Params:
    - Response:
        - Success:
            - Status: 200
          ```
          {
             "success": true,
             "data": {
                "id": "5e8b09de9933f",
                "email": "quocdaijr@gmail.com",
                "phone": "0797113505",
                "name": "Nguyễn Quốc Đại",
                "gender": "1",
                ...
                "created": 1657019573
             },
             "code": 200
          }
          ```
        - Error:
            - Status: 401
          ```
          {
             "success":false,
             "code":401,
             "message":"Thành viên chưa đăng nhập."
          }
          ``` 
    - Ex:
      ```
        axios({
            method: 'GET',
            url: 'https://beta-sso.tuoitre.vn/api/front/v1/auth/info',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                console.log("Get Info Success");
                console.log(response.data);
            } else {
                console.log("Not Login");
            }
        }).catch((error) => {
            console.log(error)
        });
      ```

4. #### Logout:

    - Description: Sử dụng redirect đến trang login trên site SSO
    - Url: /auth/logout
    - Required: Share Cookie
    - Method: POST
    - Params:
    - Response:
        - Success:
            - Status: 200
          ```
          {
             "success": true,
             "code": 200
          }
          ```
        - Error:
            - Status: 401
          ```
          {
             "success": false,
             "code": 401,
             "message": "Thành viên chưa đăng nhập."
          }
          ``` 
    - Ex:
    ```
    axios({
        method: 'POST',
        url: 'https://beta-sso.tuoitre.vn/api/front/v1/auth/logout',
        headers: {
            'Accept': 'application/json'
        },
        withCredentials: true
    }).then((response) => {
        if (response.status === 200) {
            alert("Logout Success");
        } else {
            alert("Logout Error")
        }
    }).catch((error) => {
        console.log(error)
    });
    ```

5. #### Charge Star (deprecated)
    - Description: Sử dụng để charge tiền cho gói sao
    - Url: /star-packages/charge
    - Required: Share Cookie
    - Method: POST
    - Data:
        - code: Mã gói sao (Danh sách gói: "TTSAO-50", "TTSAO-150", "TTSAO-200", "TTSAO-300", "TTSAO-500", "TTSAO-1000")
        - redirectUrl: Callback Url ("http://test.tuoitre.vn/star")
    - Response:
        - Success:
            - Status: 200
              ```
                {
                    "success": true,
                    "chargeUrl": "https://beta-sso.tuoitre.vn/star-packages/charge?signature=eyJpdiI6InFPd0Joa21pTXNrWTB...Q3MTU4YSIsInRhZyI6IiJ9",
                    "code": 200
                }
              ```
        - Failed:
            - Status: 400, 401, 422,...
              ```
                {
                   "success": false,
                   "code": 401,
                   "message": "Thành viên chưa đăng nhập."
                }
              ```
    - Next Step:
        - Thực hiện redirect `chargeUrl` được trả về ở response để redirect qua trang các phương thức thanh toán và tiến
          hành thanh toán.
        - Sau khi thực hiện thanh toán xong (thành công hoặc thất bại) sẽ redirect về `redirectUrl` (đã nhận ở trên))
          với param:
            - `redirectUrl?success=true` khi thanh toán thành công
            - `redirectUrl?success=false` khi thanh toán thất bại

    - Ex:
      ```
        axios({
            method: 'POST',
            url: 'https://beta-sso.tuoitre.vn/api/front/v1/star-packages/charge',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                code: "TTSAO-1000",
                redirectUrl: "https://tuoitre.vn/callback/test"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200 && response.data?.chargeUrl === null) {
                window.open(response.data.chargeUrl)
                console.log(response)
            } else {
                alert("Error")
            }
        }).catch((error) => {
            console.log(error)
        });
      ```

6. #### Subscription (deprecated)
    - Description: Sử dụng để đăng kí gói dịch vụ
    - Url: /subscriptions
    - Required: Share Cookie
    - Method: POST
    - Data:
        - code: Mã gói dịch vụ (Danh sách gói: "TTDB-1M", "TTDB-6M")
    - Response:
        - Success:
            - Status: 200
             ```
                {
                    "success": true,
                    "message": "Mua gói thành công",
                    "data": [],
                    "code": 200
                }
             ```
        - Error:
            - Status: 400, 422,...
              ```
                {
                    "success": false,
                    "error": "Gói đọc báo vẫn còn hạn, không thể mua thêm gói mới",
                    "code": 400
                }
              ```
    - Ex:
      ```
        axios({
            method: 'POST',
            url: 'https://beta-sso.tuoitre.vn/api/front/v1/subscriptions',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                code: "TTDB-6M"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                console.log(response)
            } else {
                alert("Error")
                console.log(response.data)
            }
        }).catch((error) => {
            console.log(error)
            alert(error)
        });
      ```

7. #### Donate
    - Description: Sử dụng để donate SAO cho bài viết
    - Url: /donations
    - Required: Share Cookie
    - Method: POST
    - Data:
        - numberOfStars: Số SAO donate
        - articleLink: Link bài viết được donate
    - Response:
        - Success:
            - Status: 200
             ```
                {
                    "success": true,
                    "message": "Donate thành công",
                    "code": 200,
                    "type": 1
                }
             ```
        - Error:
            - Status: 400, 401, 422,...
              
              Không đủ sao
              ```
                {
                    "success": false,
                    "error": "Bạn không đủ sao để donate",
                    "code": 400,
                    "type": 23
                }
              ```
              Chưa login
              ```
                {
                    "success": false,
                    "code": 401,
                    "error": "Thành viên chưa đăng nhập.",
                    "type": 5
                }
              ```
              Params truyền vào không hợp lệ
              ```
                {
                    "success": false,
                    "message": "Tham số không hợp lệ.",
                    "error": "Article link phải là một URL hợp lệ.",
                    "code": 422
                    "type": 3
                }
              ```
    - Ex:
      ```
        axios({
            method: 'POST',
            url: 'https://beta-sso.tuoitre.vn/api/front/v1/donations',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                numberOfStars: 5,
                articleLink: "https://tuoitre.vn/novaland-cam-co-co-phan-tai-hai-cong-ty-de-dam-bao-cho-khoan-vay-100-trieu-usd-20221207221051292.htm"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                console.log(response)
            } else {
                alert("Error")
                console.log(response.data)
            }
        }).catch((error) => {
            console.log(error)
            alert(error)
        });
      ```

8. #### Charge Subscription and Star
    - Description: Sử dụng để thanh toán hoặc đăng kí gói dịch vụ và mua SAO
    - Url: /subscriptions-and-stars
    - Required: Share Cookie
    - Method: POST
    - Data:
        - subscriptionCode: Mã gói dịch vụ (Danh sách gói: "TTDB-1M", "TTDB-6M")
        - starCode: Mã gói dịch vụ (Danh sách gói: "100SAO", "500SAO", "CUSTOM")
        - numberOfStars: Số SAO muốn mua (Phải lớn hơn hoặc bằng 10) (Khi starCode === CUSTOM)
        - redirectUrl: Link callback sau khi charge Url ("http://test.tuoitre.vn/star")
    - Response:
        - Success:
            - Status: 200
             
             (Khi đăng kí gói khác FREE hoặc số sao >= 10 (Yêu cầu thanh toán))
             ```
                {
                    "success": true,
                    "requireCharge": true,
                    "message": "Chuyển hướng chargeUrl để thanh toán",
                    "chargeUrl": "http://sso-member.tuoitre.local/subscriptions-and-stars/charge?signature=eyJpdiI6ImtpczRo...",
                    "code": 200
                    "type": 1
                }
             ```
             (Khi đăng kí gói FREE + 0 sao thành công (Không yêu cầu thanh toán))
             ```
                 {
                     "success": true,
                     "requireCharge": false,
                     "message": "Mua gói thành công",
                     "code": 200
                     "type": 1
                 }
             ```
        - Error:
            - Status: 400, 401, 405, 422, 429, 500
              
              Đã sử dụng gói FREE trước đó rồi.
              ```
                {
                    "success": false,
                    "error": "Bạn đã sử dụng gói này, không thể mua lại.",
                    "code": 400,
                    "type": 20
                }
              ```
              Bạn không thể đăng kí gói miễn phí khi đang sử dụng gói trả phí.
              ```
                {
                    "success": false,
                    "error": "Bạn không thể đăng kí gói miễn phí khi đang sử dụng gói trả phí.",
                    "code": 400,
                    "type": 21
                }
              ```
              Param redirectUrl để null khi cần thanh toán gói hoặc sao
              ```
                {
                    "success": false,
                    "error": "Redirect url không được để trống khi cần thực hiện thanh toán.",
                    "code": 422,
                    "type": 22
                }
              ```
              Chưa login
              ```
                {
                    "success": false,
                    "code": 401,
                    "error": "Thành viên chưa đăng nhập.",
                    "type": 5
                }
              ```
              Params truyền vào không hợp lệ
              ```
                {
                    "success": false,
                    "message": "Tham số không hợp lệ.",
                    "error": "Number of stars phải lớn hơn 10.",
                    "code": 422,
                    "type": 3
                }
              ```
    - Ex:
      ```
        axios({
            method: 'POST',
            url: 'https://beta-sso.tuoitre.vn/api/front/v1/subscriptions-and-stars',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                subscriptionCode: "TTDB-6M",
                numberOfStars: 10,
                redirectUrl: "https://tuoitre.vn/callback/test"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                if ((response.data?.requireCharge) && response.data?.chargeUrl) {
                    window.open(response.data?.chargeUrl ?? null)
                } else {
                    alert(response.data?.message)
                }
                console.log(response)
            } else {
                alert("Error")
            }
        }).catch((error) => {
            console.log(error)
            alert(error)
        });
      ```

### Response Type Constant:

- TYPE_SUCCESS = 1; 
- TYPE_ERROR_OTHER = 2;
- TYPE_ERROR_PARAMS_INVALID = 3;
- TYPE_ERROR_UNAUTHENTICATED = 5;
- TYPE_ERROR_FREE_PACKAGE_ALREADY_USED = 20;
- TYPE_ERROR_FREE_PACKAGE_LOCKED = 21;
- TYPE_ERROR_REDIRECT_URL_REQUIRED_WHEN_CHARGED = 22;
- TYPE_ERROR_AMOUNT_NOT_ENOUGH = 23;
- TYPE_ERROR_INVALID_PACKAGE_AND_STAR = 24;
- Updating...

## Example TEST:

- Url: https://beta-client.tuoitre.vn/test-sso

[//]: # (- File: [here]&#40;files/sso_redirect_login.html&#41;)

[//]: # (- Note: Set up with domain like `xyz.tuoitre.vn` to be able to share cookies with SSO)
        