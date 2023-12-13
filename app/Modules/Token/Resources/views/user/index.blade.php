@extends('token::layouts.master-token')


@section('title', 'CMS Token')
@section('content')


    <div class="container p-0 mt-5 mb-5">
        <section class="mymain">
            @include('token::layouts.header', ['data' => $data ?? null])
            <main>
                <div class="d-flex justify-content-between">
                    <div class="myf6">Danh sách USer</div>
                    <div>
                        <button type="button" class="btn mybt1" id="btn-add-detail" data-bs-toggle="modal" data-bs-target="#addDetail">Thêm user</button>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between myd2">
                    <div class="row myd1">
                        <div class="col-2 position-relative my-hover">
                            <label class="position-absolute a1">Username</label>
                            <input type="text" id="t1" class="form-control myinput2" aria-describedby="text1">
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" placeholder="Email" aria-label="State">
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" placeholder="Phone" aria-label="Zip">
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn mybt2">Tìm kiếm</button>
                    </div>
                </div>
                <div>
                    <table class="table table-xs" id="mytable">
                        <thead>
                        <tr class="mt-3">
                            <th  scope="col">
                                <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Username
                                </a>
                            </th>
                            <th  scope="col">
                                <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Email
                                </a>
                            </th>
                            <th  scope="col">
                                <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Phone
                                </a>
                            </th>
                            <th  scope="col">
                                <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Status
                                </a>
                            </th>
                            <th  scope="col">
                                <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="btn-open-detail" >
                            <th  scope="row">khang nguyen</th>
                            <td >khan@tuoitre.com.vn</td>
                            <td >09213413213</td>
                            <td class="text-center"><span class="mylabel1">Disable</span></td>
                            <td >
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update" id="btn-confirm" data-bs-toggle="modal" data-bs-target="#confirm"><i class="fa fa-unlock-alt" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr id="btn-open-detail" >
                            <th  scope="row">khang nguyen</th>
                            <td >khan@tuoitre.com.vn</td>
                            <td >09213413213</td>
                            <td class="text-center"><span class="mylabel2">Enable</span></td>
                            <td >
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update" id="btn-confirm" data-bs-toggle="modal" data-bs-target="#confirm"><i class="fa fa-lock" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr id="btn-open-detail" >
                            <th  scope="row">khang nguyen</th>
                            <td >khan@tuoitre.com.vn</td>
                            <td >09213413213</td>
                            <td class="text-center"><span class="mylabel1">Disable</span></td>
                            <td >
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update" id="btn-confirm" data-bs-toggle="modal" data-bs-target="#confirm"><i class="fa fa-unlock-alt" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr id="btn-open-detail" >
                            <th  scope="row">khang nguyen</th>
                            <td >khan@tuoitre.com.vn</td>
                            <td >09213413213</td>
                            <td class="text-center"><span class="mylabel2">Enable</span></td>
                            <td >
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm mybt3" data-bs-toggle="tooltip" data-bs-placement="top" title="Update" id="btn-confirm" data-bs-toggle="modal" data-bs-target="#confirm"><i class="fa fa-lock" aria-hidden="true"></i></button>
                            </td>
                        </tr>


                        </tbody>
                    </table>

                    <!-- ############################################################################ -->
                    <div class="modal my-modal fade" id="formDetail" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title my-title" id="passModalLabel">CHI TIẾT SERVICE</h5>
                                    <button type="button" class="btn-close mybt4" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table mb-0" >
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

                    <div class="d-flex justify-content-between ms-3 me-3 mt-3">
                        <div class="myf7">Hiển thị 1 đến 10 của 90</div>
                        <div>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
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


</body>

</html>
