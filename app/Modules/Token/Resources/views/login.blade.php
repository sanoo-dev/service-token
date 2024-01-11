@extends('token::layouts.master_token')

@section('title', 'CMS Token')
@section('content')
    <div class="container-fluid p-0 mb-5">
        <header>
            <p class="mylogo"><img src="{{mix('img/logologin.png')}}" title=""/></a></p>
            <p class="myf1">Đăng nhập</p>
            <p class="myf2">Nhập tài khoản và mật khẩu của bạn</p>
        </header>

        <section class="section section-login">
            <div class="container d-flex justify-content-center">
                <div class="box-center">
                    <form class="frm-general frm-login needs-validation" novalidate>
                        <input type="email" class="form-control mb-3" id="txt-mail" placeholder="Tài khoản"
                               aria-describedby="emailHelp" required>
                        <input type="password" id="password-field-login" class="form-control mt-3"
                               placeholder="Mật khẩu" aria-describedby="passwordHelpBlock" required>
                        <span toggle="#password-field-login"
                              class="fa fa-fw fa-eye field-icon toggle-password-login"></span>
                        <p class="d-flex justify-content-between mt-4">
                            <span class="myf3"><input class="form-check-input" type="checkbox" value=""
                                                      id="flexCheckDefault">&nbsp;Ghi nhớ đăng nhập</span>
                            {{--                            <a class="myf4" href="#" title="">Quên mật khẩu?</a>--}}
                        </p>

                        <p class="d-flex justify-content-center">
                            <button type="submit" class="btn my-4 myb1">Đăng nhập</button>
                        </p>
                    </form>
                </div>
            </div>
        </section>

    </div>

    <script>
        $(".toggle-password-login").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                document.getElementById('password-field-login').setAttribute('type', 'text');
            } else {
                document.getElementById('password-field-login').setAttribute('type', 'password');
            }
        });
    </script>

@endsection
