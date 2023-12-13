<?php

$currentDomain = request()->getSchemeAndHttpHost();
$ssoDomain = env('APP_TEST_URL');
?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <title>Test SSO Redirect Login</title>
</head>
<body>

<div>
    <button onclick="login()" class="login-btn">Login</button>
    {{--    <button onclick="socialLogin('google')" class="login-btn">Login By Google</button>--}}
    {{--    <button onclick="socialLogin('facebook')" class="login-btn">Login By Facebook</button>--}}
    <div class="info-txt" style="width: min-content;">
        <p>Hello <span class="name-txt"></span></p>
        <p>Access To Site <a href="{{$ssoDomain}}" target="_blank">{{$ssoDomain}}</a></p>
        <p>Logout</p>
        <button style="margin-top: 5px;" onclick="logout()">Logout</button>
        {{--<p>Charge Star (deprecated)</p>
        <button style="margin-top: 5px;" onclick="chargeStar()">Charge Star Package</button>
        <p>Subscribe (deprecated)</p>
        <button style="margin-top: 5px;" onclick="subscribe()">Subscribe</button>--}}
        <p>Donate</p>
        <button style="margin-top: 5px;" onclick="donate()">Donate</button>
        <p>Charge Subscription And Star</p>
        <p>(New))</p>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTC-52KY', null, null)">Subscribe And Star With
            Subscription TTC-52KY And Star Code null and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-30D', null, null)">Subscribe And Star With
            Subscription TTDB-30D And Star Code null and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-30D', '100SAO', null)">Subscribe And Star With
            Subscription TTDB-30D And Star Code 100SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', '500SAO', null)">Subscribe And Star With
            Subscription TTDB-6M And Star Code 500SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-12M', '1000SAO', null)">Subscribe And Star With
            Subscription TTDB-12M And Star Code 1000SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', 'CUSTOM', null)">Subscribe And Star With
            Subscription TTDB-6M And Star Code CUSTOM and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', 'CUSTOM', 150)">Subscribe And Star With
            Subscription TTDB-6M And Star Code CUSTOM and Star 150
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', null)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', 88)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star 88
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', 111)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star 111
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', 552)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star 552
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', 1101)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star 1101
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'CUSTOM', 3444)">Subscribe And Star With
            Subscription null And Star Code CUSTOM and Star 3444
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, '100SAO', null)">Subscribe And Star With
            Subscription null And Star Code 100SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, '500SAO', null)">Subscribe And Star With
            Subscription null And Star Code 500SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, '1000SAO', null)">Subscribe And Star With
            Subscription null And Star Code 1000SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, '100SAO', 50)">Subscribe And Star With
            Subscription null And Star Code 100SAO and Star 50 (will ignore 50 sao)
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, 'INVALID_SAO', null)">Subscribe And Star With
            Subscription null And Star Code INVALID_SAO and Star null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('INVALID_SUB', null, null)">Subscribe And Star With
            Subscription INVALID_SUB And Star Code null and Star null
        </button>
        <p>(Old)</p>
        <button style="margin-top: 10px;" onclick="subscribeAndStar()">Subscribe And Star Null All</button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', null, null)">Subscribe And Star Only
            Subscription TTDB-6M
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-30D', null, null)">Subscribe And Star Only
            Subscription TTDB-1M
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, null, 0)">Subscribe And Star Only Star 0
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar(null, null, 10)">Subscribe And Star Only Star 10
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-30D', null, 0)">Subscribe And Star With
            Subscription TTDB-1M And Star 0
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-30D', null, 10)">Subscribe And Star With
            Subscription TTDB-1M And Star 10
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', null, 10)">Subscribe And Star With
            Subscription TTDB-6M And Star 10
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', null, 10, null)">Subscribe And Star With
            Subscription TTDB-6M And Star 10 and redirectUrl null
        </button>
        <button style="margin-top: 5px;" onclick="subscribeAndStar('TTDB-6M', null, null, null)">Subscribe And Star With
            Subscription TTDB-6M And Star null and redirectUrl null
        </button>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js" crossorigin="anonymous"></script>
<script>
    function login() {
        // window.open("http://sso-member.tuoitre.local/auth/v1/login?redirectUrl=http://aaaa.tuoitre.vn:8005/test");
        axios({
            method: 'GET',
            url: '{{$ssoDomain}}/api/front/v1/auth/login?redirectUrl={{$currentDomain}}/test-sso',
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
    }

    function info() {
        axios({
            method: 'GET',
            url: '{{$ssoDomain}}/api/front/v1/auth/info',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                document.getElementsByClassName('login-btn')[0].style.display = 'none'
                document.getElementsByClassName('info-txt')[0].style.display = 'block'
                document.getElementsByClassName('name-txt')[0].textContent = response.data.data.name
            } else {
                document.getElementsByClassName('info-txt')[0].style.display = 'none'
                document.getElementsByClassName('login-btn')[0].style.display = 'block'
            }
        }).catch((error) => {
            console.log(error)
        });
    }

    function checkLogin() {
        axios({
            method: 'GET',
            url: '{{$ssoDomain}}/api/front/v1/auth/login/check',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                document.getElementsByClassName('login-btn')[0].style.display = 'none'
                document.getElementsByClassName('info-txt')[0].style.display = 'block'
                document.getElementsByClassName('name-txt')[0].textContent = response.data.data.name
            } else {
                document.getElementsByClassName('info-txt')[0].style.display = 'none'
                document.getElementsByClassName('login-btn')[0].style.display = 'block'
            }
        }).catch((error) => {
            console.log(error)
        });
    }

    function logout() {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/api/front/v1/auth/logout',
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                document.getElementsByClassName('login-btn')[0].style.display = 'block'
                document.getElementsByClassName('info-txt')[0].style.display = 'none'
            } else {
                alert("Error")
            }
        }).catch((error) => {
            console.log(error)
        });
    }

    function chargeStar() {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/api/front/v1/star-packages/charge',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                code: "TTSAO-1000",
                redirectUrl: "{{$currentDomain}}/test-sso"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                window.open(response.data?.chargeUrl ?? null)
                console.log(response)
            } else {
                alert("Error")
            }
        }).catch((error) => {
            console.log(error)
        });
    }

    function subscribe() {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/api/front/v1/subscriptions',
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
                alert(response.data)
            } else {
                alert("Error")
                alert(response.data)
            }
        }).catch((error) => {
            console.log(error)
            alert(error)
        });
    }

    function donate() {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/api/front/v1/donations',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                numberOfStars: 5,
                articleLink: "https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies"
            },
            withCredentials: true
        }).then((response) => {
            if (response.status === 200) {
                console.log(response)
                alert(response.data)
            } else {
                alert("Error")
                alert(response.data)
            }
        }).catch((error) => {
            console.log(error)
            alert(error)
        });
    }

    function subscribeAndStar(subscriptionCode = null, starCode = null, numberOfStars = null, redirectUrl = "{{$currentDomain}}/test-sso") {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/api/front/v1/subscriptions-and-stars',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                subscriptionCode: subscriptionCode,
                starCode: starCode,
                numberOfStars: numberOfStars,
                redirectUrl: redirectUrl
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
    }

    function socialLogin(driver) {
        axios({
            method: 'POST',
            url: '{{$ssoDomain}}/social-login/' + driver,
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        }).then((response) => {
            console.log(response.data)
            // if (response.data?.login_url) {
            //     window.open(response.data?.login_url, '_self')
            // } else {
            //     alert("Error")
            // }

        }).catch((error) => {
            console.log(error)
        });
    }

    info()
    checkLogin()
</script>
</body>
</html>