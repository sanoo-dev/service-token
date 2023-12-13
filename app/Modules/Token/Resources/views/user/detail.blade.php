@extends('token::layouts.master-token')


@section('title', 'CMS Token')
@section('content')


    <div class="container p-0 mt-5 mb-5">
        <section class="mymain">
            @include('token::layouts.header', ['data' => $data ?? null])
            <main class="container mywrap">
                <div class="row pt-5">
                    <p class="text-center myf10">Thông tin cá nhân</p>
                    <p class="text-center"><svg width="91" height="3" viewBox="0 0 91 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="91" height="3" rx="1" fill="#34465C"/>
                        </svg></p>
                    <div class="col-6 mx-auto mytab22 p-5">
                        <table class="table table-borderless mytab_bg" >
                            <tbody>
                            <tr>
                                <td class="tb1" style="text-align: center;"> Username</td>
                                <td class="tb3 text-start"> Tuyển sinh hub</td>
                                <td > &nbsp;</td>
                                <td class="tb1"> <a href="#"> Cập nhật</a></td>
                            </tr>
                            <tr>
                                <td class="tb1" style="text-align: center;"> Email</td>
                                <td class="tb3 text-start"> fsfe@gmail.com</td>
                                <td > &nbsp;</td>
                                <td class="tb1"> <a href="#"> Cập nhật</a></td>
                            </tr>
                            <tr>
                                <td class="tb1" style="text-align: center;"> Điện thoai </td>
                                <td class="tb3 text-start"> 0912312312</td>
                                <td > &nbsp;</td>
                                <td class="tb1"> <a href="#"> Cập nhật</a></td>
                            </tr><tr>
                                <td class="tb1" style="text-align: center;"> Mật khẩu</td>
                                <td class="tb3 text-start"> *********</td>
                                <td > &nbsp;</td>
                                <td class="tb1"> <a href="#"> Cập nhật</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ############################################################################ -->
                    <div class="modal my-modal fade" id="formDetail" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title my-title" id="passModalLabel">CHI TIẾT SERVICE</h5>
                                    <button type="button" class="btn-close mybt4" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table" >
                                        <tbody>
                                        <tr>
                                            <td class="tb1"> Tên service</td>
                                            <td class="tb2"> Tuyển sinh hub</td>
                                        </tr>
                                        <tr>
                                            <td class="tb1"> Domain</td>
                                            <td class="tb2"> Tuyeninhhun.com.vn</td>
                                        </tr>
                                        <tr>
                                            <td class="tb1"> IP Endpoint</td>
                                            <td class="tb2"> 192.168.1.123</td>
                                        </tr>
                                        <tr>
                                            <td class="tb1"> Domain Endpoint</td>
                                            <td class="tb2"> 192.168.55.123</td>
                                        </tr>
                                        <tr>
                                            <td class="tb1"> Partner Code</td>
                                            <td class="tb2"> epnfshirerhthverhthrehterhther</td>
                                        </tr>
                                        <tr>
                                            <td class="tb1 mb-2"> Secretkey</td>
                                            <td class="tb2 mb-2"> keyfshirhotheverhthrehterhther</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ############################################################################ -->
                    <div class="modal my-modal fade" id="addDetail" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title my-title" id="passModalLabel">THÊM USER</h5>
                                    <button type="button" class="btn-close mybt4" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form row" id="form-name" action="#" method="post" data-url-login-by-password="#">

                                        <label class="col-12 pt-3 position-relative my-hover">
                                            <label class="position-absolute a2">Email</label>
                                            <input type="text" id="t1" class="form-control myinput2" aria-describedby="text1">
                                        </label>
                                        <label class="col-12 pt-3 my-hover">
                                            <input type="text" class="form-control" placeholder="Phone" aria-label="State">
                                        </label>
                                        <label class="col-12 pt-3">
                                            <input type="text" class="form-control" placeholder="Username" aria-label="State">
                                        </label>
                                        <label class="col-12 pt-3">
                                            <input type="password" id="password-field-login" class="form-control mt-3" placeholder="Mật khẩu" aria-describedby="passwordHelpBlock" required>
                                            <span toggle="#password-field-login" class="fa fa-fw fa-eye field-icon toggle-password-login"></span>
                                        </label>

                                        <div class="col-12 text-center mt-3">
                                            <button type="button" class="btn mybt6 fw-bolder">Hủy</button>
                                            <button type="button" class="btn mybt5 fw-bolder">Tạo</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ############################################################################ -->
                    <div class="modal my-modal fade" id="confirm" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title my-title" id="confirmModalLabel">&nbsp;</h5>
                                    <button type="button" class="btn-close mybt4" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <p class="col-12 text-center"><img  class="mypos2" src="img/confirm.png" /></p>
                                        <p class="mt-2 col-12 myf8 ">Bạn có chắc chắn khóa user này</p>
                                        <div class="col-12 text-center mt-3">
                                            <button type="button" class="btn mybt6 fw-bolder">Hủy</button>
                                            <button type="button" class="btn mybt5 fw-bolder">Dừng</button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </main>
        </section>

    </div>



@endsection





<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    $(".toggle-password-login").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            document.getElementById('password-field-login').setAttribute('type', 'text');
        } else {
            document.getElementById('password-field-login').setAttribute('type', 'password');
        }
    });

</script>



