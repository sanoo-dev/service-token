@extends('token::layouts.master-token')

@push('stylesheet')
    <link href="{{mix('css/bootstrap.min.css')}} " rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    <div class="container p-0 mt-5 mb-5">

            <main>
                <p class="mylogo"><img src="{{mix('img/logologin.png')}}" title="" /></p>
            </main>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $(".toggle-password-login").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                document.getElementById('password-field-login').setAttribute('type', 'text');
            } else {
                document.getElementById('password-field-login').setAttribute('type', 'password');
            }
        });

        function confirmStatusActivityService(id) {

            var span = document.getElementById("clearbtnsctivity1"+id);
            var idText = document.getElementById("textStatus1"+id);
            if (span.classList.contains('fa-play')) {
                if (confirm("Bạn có muốn mở hoạt động?")) {
                    $.ajax({
                        url: '/token/update-service', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                        method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                        dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                        data: {
                            id: id,
                            status: 10,
                            // Thêm các tham số khác nếu cần thiết
                        }, success: function (data) {
                            // Xử lý dữ liệu trả về thành công

                            span.classList.remove("fa-play");
                            span.classList.add("fa-pause");
                            idText.classList.remove("mylabel1");
                            idText.classList.add("mylabel2");
                            $(document).ready(function () {
                                // Remove existing text and add new text
                                $('#textStatus1'+id).text('Activity');
                            });
                        },
                        error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });
                }
            } else {
                if (confirm("Bạn có muốn tắt hoạt động?")) {
                    // Chuyển hướng đến trang xử lý chỉnh sửa

                    $.ajax({
                        url: '/token/update-service', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                        method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                        dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                        data: {
                            id: id,
                            status: 20,
                            // Thêm các tham số khác nếu cần thiết
                        }, success: function (data) {
                            // Xử lý dữ liệu trả về thành công

                            $(document).ready(function () {
                                // Remove existing text and add new text
                                span.classList.remove("fa-pause");
                                span.classList.add("fa-play");
                                idText.classList.remove("mylabel2");
                                idText.classList.add("mylabel1");
                                $('#textStatus1'+id).text('Stop');
                            });
                        },
                        error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });
                }
            }

        }
        function  onClickDetailService(id){
            fetch('/token/list-service?id=' + id, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })

                .then(response => response.text())
                .then(data => {
                    // Xử lý dữ liệu trả về thành công
                    var log = renderHtml(JSON.parse(data))
                    var html = document.getElementById('detailService' );
                    html.innerHTML = log

                })
                .catch(error => {
                    console.log('Error:', error);
                });

        }
        function renderHtml(data) {
            var html=''

            if (data != "") {

                data.list.forEach(
                    item => {
                        {

                            html+=`
                       <tbody>
                       <tr>
                       <td class="tb1"> Tên endpoint</td>
                    <td class="tb2"> ${item.appName}</td>
                </tr>
                    <tr>
                        <td class="tb1"> Secret Key</td>
                        <td class="tb2">${item.secretKey}</td>
                    </tr>
                    <tr>
                        <td class="tb1"> Partner Code</td>
                        <td class="tb2">${item.partnerCode}</td>
                    </tr>
                    <tr>
                        <td class="tb1"> Domain</td>
                        <td class="tb2">${item.domain}</td>
                    </tr>
                    <tr>
                        <td class="tb1"> IP</td>
                        <td class="tb2">${item.serveIp}</td>
                    </tr>
                </tbody>
                    `
                        }
                    });
            }

            return html
        }

    </script>

@endpush







