@extends('token::layouts.master-token')

@push('stylesheet')
    <link href="{{mix('css/bootstrap.min.css')}} " rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    @include('token::layouts.header', ['data' => $data ?? null])
    <main class="container mymain">
        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb ">
                <li class="breadcrumb-item"><a href="#">Endpoint</a></li>
                <li class="breadcrumb-item " aria-current="page">Danh Sách Endpoint</li>
            </ol>
        </nav>
        @include('token::endpoint.layouts.header')
        <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
            <div>
                <table class="table table-xs" id="mytable">
                    @include('token::endpoint.layouts.header_table')
                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $key=>$item)

                            <tr id="changeColor{{$key}}" class="h-100 ">
                                <th class="align-middle mcl1 accordion-button mlevel1 collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$key}}"
                                    aria-expanded="true" aria-controls="collapse{{$key}}" scope="row"
                                    onclick=" changeColor({{$key}});loadData({{$key}},{{$item['db_id']}});  loadDataService({{$key}},'{{$item['domain']}}','{{$item['serveIp']}}')"
                                > {{$item['name']??null}}</th>
                                <td class="align-middle">{{$item['domain']}}</td>
                                <td class="align-middle">{{$item['serveIp']}}</td>
                                @if($item['status']==\App\Modules\Token\Helpers\Constants\ConstantDefine::ACTIVITY)
                                    <td   id="clearbtnsctivityendpont1{{$item['db_id']}}" class="align-middle text-center text-success">
                                        <button class="btn myb14 " id="textStatusendpoint1{{$item['db_id']}}"
                                                onclick="confirmStatusActivityEndPoint({{$item['db_id']}})">
                                            <i
                                                class="fa fa-play"></i>
                                            Enable
                                        </button>
                                    </td>
                                @else
                                    <td id="clearbtnsctivityendpont1{{$item['db_id']}}"  class="align-middle">
                                        <button class="btn myb15 p-0" id="textStatusendpoint1{{$item['db_id']}}"
                                                onclick="confirmStatusActivityEndPoint({{$item['db_id']}})">
                                            <i
                                               class="fa fa-pause"
                                               aria-hidden="true"></i>
                                            Disable
                                        </button>
                                    </td>

                                @endif
                                <td class="align-middle text-center">{{!empty($item['created_at'])?\Carbon\Carbon::createFromTimestamp($item['created_at'])->format('d/m/Y'):''}}</td>
                            </tr>

                            <tr class="hide-table-padding">
                                <td colspan="8" class="ps-0 pe-0">
                                    <div id="collapse{{$key}}" class="collapse in p-3">
                                        <div class="accordion mypanel" id="accordionExample">
                                            <div class="accordion-item">
                                                <div class="accordion-header d-flex justify-content-between"
                                                     id="headingOne">
                                                    <button class="accordion-button" href="#" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{$key}}Child" aria-expanded="true"
                                                            aria-controls="collapse{{$key}}Child">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Key
                                                    </button>
                                                    <div class="float-end">
                                                        <span>REQUEST</span>
                                                        <button class="mybt8 btn dropdown-toggle" type="button"
                                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                            <svg width="4" height="20" viewBox="0 0 4 20" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="2" cy="18" r="2" fill="#2E4162"/>
                                                                <circle cx="2" cy="10" r="2" fill="#2E4162"/>
                                                                <circle cx="2" cy="2" r="2" fill="#2E4162"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                                            <li><a class="dropdown-item" href="#">Another action</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#">Something else
                                                                    here</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key}}Child" class="accordion-collapse collapse show"
                                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <table class="table table-xs mytable1">
                                                            <thead>
                                                            <tr class="mt-3">
                                                                <th scope="col">
                                                                    Key
                                                                </th>
                                                                <th scope="col">
                                                                    Action
                                                                </th>

                                                            </tr>
                                                            </thead>
                                                            <tbody id="endPointdatakey{{$key}}">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="accordion-header d-flex justify-content-between"
                                                     id="headingOne">
                                                    <button class="accordion-button" href="#" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{$key}}2Child" aria-expanded="true"
                                                            aria-controls="collapse{{$key}}2Child">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Service
                                                    </button>
                                                    <div class="float-end">
                                                        <span>REQUEST</span>
                                                        <button class="mybt8 btn dropdown-toggle" type="button"
                                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                            <svg width="4" height="20" viewBox="0 0 4 20" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="2" cy="18" r="2" fill="#2E4162"/>
                                                                <circle cx="2" cy="10" r="2" fill="#2E4162"/>
                                                                <circle cx="2" cy="2" r="2" fill="#2E4162"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                                            <li><a class="dropdown-item" href="#">Another action</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#">Something else
                                                                    here</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key}}2Child" class="accordion-collapse collapse show"
                                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <table class="table table-xs mytable1">
                                                            <thead>
                                                            <tr class="mt-3">
                                                                <th scope="col">
                                                                    Tên service
                                                                </th>
                                                                <th scope="col">
                                                                    Domain
                                                                </th>
                                                                <th scope="col">
                                                                    IP
                                                                </th>
                                                                <th scope="col">
                                                                    STATUS
                                                                </th>

                                                            </tr>
                                                            </thead>
                                                            <tbody id="endPointdataservice{{$key}}">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <!-- ############################################################################ -->
                <div class="modal my-modal fade" id="formDetail" tabindex="-1" aria-labelledby="passModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title my-title" id="passModalLabel">CHI TIẾT ENDPOINT</h5>
                                <button type="button" class="btn-close mybt4" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if(!empty($data))

                                    @foreach($data as $key=>$item)
                                        <table class="table mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="tb1"> Tên endpoint</td>
                                                <td class="tb2"> {{$item['name']}}</td>
                                            </tr>
                                            <tr>
                                                <td class="tb1"> Domain</td>
                                                <td class="tb2">{{$item['domain']}}</td>
                                            </tr>
                                            <tr>
                                                <td class="tb1"> IP</td>
                                                <td class="tb2"> {{$item['serveIp']}}</td>
                                            </tr>


                                            </tbody>
                                        </table>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ############################################################################ -->
                @include('token::endpoint.popup.create_endpoint')
                @if(!empty($data))
                    {{ $data->links('vendor.pagination.custom')}}
                @endif

            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{mix('js/flatpickr.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy thông báo từ Session (sử dụng Laravel)
            var alertMessage = '{{!empty($code)?$code:'' }}';
            // Kiểm tra xem có thông báo hay không
            if (alertMessage==='100') {
                // Hiển thị thông báo dạng popup với SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Thông báo',
                    text: "{{!empty($message)?$message:'' }}"
                }).then((result) => {
                    // Kiểm tra xem người dùng đã tương tác với cửa sổ cảnh báo chưa
                    if (result.isConfirmed || result.isDismissed) {
                        // Sử dụng setTimeout để chờ 10 giây trước khi redirect
                        setTimeout(() => {
                            window.location.href = '{{env('URL_ERP_LOGIN').env('URL_TOKEN')}}';
                        }, 1000); // Thời gian đợi là 10000 milliseconds (10 giây)
                    }
                });
            }
            if (alertMessage==='42')  {
                // Hiển thị thông báo dạng popup với SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Thông báo',
                    text: "{{!empty($message)?$message:'' }}"
                }).then(function() {
                    // Redirect sau khi người dùng ấn OK
                    window.location.href = '{{env('APP_URL').'/token/manage-endpoints'}}';
                });
            }
        });


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
        $(".flatpickr-input").flatpickr({
            enableTime: true, dateFormat: "d-m-Y H:i",
        });

        // ajax call data
        function loadData(key, id) {
            fetch('/token/list-endpoints?id=' + id, {
                method: 'GET', headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.text())
                .then(data => {


                    var log = renderHtml(JSON.parse(data))
                    var html = document.getElementById('endPointdatakey' + key);
                    html.innerHTML = log


                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }

        function renderHtml(data) {
            var temp = ''
            var newKey = ''
            if (data != "") {
                data.list.forEach(item => {
                    temp += `
                        <td id="oldkey${item.db_id}"  style="overflow-x:hidden; white-space: nowrap;width:100px; max-width:600px; vertical-align: inherit;text-overflow: ellipsis;"
        >${item.publicKey}</td>
        <td><i style="width:30px; max-width:20px;cursor: pointer;"  class="fa fa-clipboard" onclick="copyText(0)"></i></td>

        `

                });
            } else {
                var temp = ''
                temp += `    <tr>
                        <td style="overflow-x:hidden; white-space: nowrap;width:50px; max-width:350px; vertical-align: inherit;text-overflow: ellipsis;"
        ></td></tr>`

            }
            return temp
        }

        function copyText(key) {
            var row = event.target.parentNode.parentNode; // Lấy tham chiếu đến thẻ tr
            var textToCopy = row.cells[key].innerText; // Lấy nội dung của ô đầu tiên trong tr

            var tempInput = document.createElement("input"); // Tạo phần tử input tạm thời
            tempInput.value = textToCopy; // Gán nội dung cần sao chép vào input
            document.body.appendChild(tempInput); // Thêm input vào body của trang

            tempInput.select(); // Chọn nội dung trong input
            document.execCommand("copy"); // Sao chép nội dung vào clipboard

            document.body.removeChild(tempInput); // Xóa input tạm thời


        }

        //load service of endpoint
        function loadDataService(key, domain, serveIp) {


            fetch('/token/list-service?domainTransfer=' + domain + '&&serveTransfer=' + serveIp + '&&type=21', {
                method: 'GET', headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.text())
                .then(data => {

                    if (data !== '') {
                        var log = renderHtmlService(JSON.parse(data), key)
                        // var html = document.getElementById('endPointdataservice' + key);
                        // html.innerHTML = log
                    }

                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }

        function renderHtmlService(data, key) {
            var temp = ''
            if (data != "") {
                var tableRow = document.getElementById('endPointdataservice' + key);
                data.list.forEach(item => {
                    const keys = Object.keys(item);
                    const row = document.createElement('tr');
                    const cell1 = document.createElement('td');
                    cell1.textContent = item.appName;
                    const cell2 = document.createElement('td');
                    cell2.textContent = item.domain;
                    const cell3 = document.createElement('td');
                    cell3.textContent = item.serveIp;
                    const cell4 = document.createElement('td');
                    cell4.setAttribute('id','btnstatus'+item.db_id);
                    if (parseInt(item.status) === 10) {
                        cell4.innerHTML = ` <button type="button" class="btn myb14" id="clearbtnsctivity1${item.db_id}"
                                            onclick="confirmStatusActivityService(${item.db_id})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                            <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                        </svg>
                                        <span id="textStatus1${item.db_id}" >Enable</span>

                                    </button>`;
                    } else {
                        cell4.innerHTML = `     <button type="button" class="btn myb15" id="clearbtnsctivity1${item.db_id}"
                                    onclick="confirmStatusActivityService(${item.db_id})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="7.5" stroke="#979797"/>
                                            <path
                                                d="M6.6543 10.4141C6.6543 10.2318 6.71615 10.0788 6.83984 9.95508C6.96354 9.82812 7.13118 9.76465 7.34277 9.76465C7.55762 9.76465 7.72526 9.82812 7.8457 9.95508C7.9694 10.0788 8.03125 10.2318 8.03125 10.4141C8.03125 10.5964 7.9694 10.7493 7.8457 10.873C7.72526 10.9967 7.55762 11.0586 7.34277 11.0586C7.13118 11.0586 6.96354 10.9967 6.83984 10.873C6.71615 10.7493 6.6543 10.5964 6.6543 10.4141ZM9.4375 10.4141C9.4375 10.2318 9.49935 10.0788 9.62305 9.95508C9.74674 9.82812 9.91439 9.76465 10.126 9.76465C10.3408 9.76465 10.5085 9.82812 10.6289 9.95508C10.7526 10.0788 10.8145 10.2318 10.8145 10.4141C10.8145 10.5964 10.7526 10.7493 10.6289 10.873C10.5085 10.9967 10.3408 11.0586 10.126 11.0586C9.91439 11.0586 9.74674 10.9967 9.62305 10.873C9.49935 10.7493 9.4375 10.5964 9.4375 10.4141ZM12.2207 10.4141C12.2207 10.2318 12.2826 10.0788 12.4062 9.95508C12.5299 9.82812 12.6976 9.76465 12.9092 9.76465C13.124 9.76465 13.2917 9.82812 13.4121 9.95508C13.5358 10.0788 13.5977 10.2318 13.5977 10.4141C13.5977 10.5964 13.5358 10.7493 13.4121 10.873C13.2917 10.9967 13.124 11.0586 12.9092 11.0586C12.6976 11.0586 12.5299 10.9967 12.4062 10.873C12.2826 10.7493 12.2207 10.5964 12.2207 10.4141Z"
                                                fill="#737373"/>
                                        </svg>
                                        <span id="textStatus1${item.db_id}" > Disable</span>
                                    </button>`;
                    }
                    row.appendChild(cell1);
                    row.appendChild(cell2);
                    row.appendChild(cell3);
                    row.appendChild(cell4);
                    tableRow.appendChild(row);
                    //  var newTd = document.createElement('td');
                    // newTd.textContent = 'Nội dung của ô';
                    //
                    // var tableRow = document.getElementById('endPointdataservice'+key);
                    // console.log(tableRow);
                    // tableRow.appendChild(newTd);
                });


            } else {

                var temp = ''
                temp += `    <tr>
                <td style="overflow-x:hidden; white-space: nowrap;width:50px; max-width:350px; vertical-align: inherit;text-overflow: ellipsis;"
></td></tr>`

            }
            return temp
        }

        function confirmStatusActivityService(id) {

            var span = document.getElementById("clearbtnsctivity1" + id);
            var idText = document.getElementById("textStatus1" + id);
            if (span.classList.contains('myb14')) {
                if (confirm("Bạn có muốn tắt hoạt động?")) {
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
                            var cell4 = document.getElementById("btnstatus"+id);
                            cell4.innerHTML = `     <button type="button" class="btn myb15" id="clearbtnsctivity1${id}"
                                    onclick="confirmStatusActivityService(${id})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="7.5" stroke="#979797"/>
                                            <path
                                                d="M6.6543 10.4141C6.6543 10.2318 6.71615 10.0788 6.83984 9.95508C6.96354 9.82812 7.13118 9.76465 7.34277 9.76465C7.55762 9.76465 7.72526 9.82812 7.8457 9.95508C7.9694 10.0788 8.03125 10.2318 8.03125 10.4141C8.03125 10.5964 7.9694 10.7493 7.8457 10.873C7.72526 10.9967 7.55762 11.0586 7.34277 11.0586C7.13118 11.0586 6.96354 10.9967 6.83984 10.873C6.71615 10.7493 6.6543 10.5964 6.6543 10.4141ZM9.4375 10.4141C9.4375 10.2318 9.49935 10.0788 9.62305 9.95508C9.74674 9.82812 9.91439 9.76465 10.126 9.76465C10.3408 9.76465 10.5085 9.82812 10.6289 9.95508C10.7526 10.0788 10.8145 10.2318 10.8145 10.4141C10.8145 10.5964 10.7526 10.7493 10.6289 10.873C10.5085 10.9967 10.3408 11.0586 10.126 11.0586C9.91439 11.0586 9.74674 10.9967 9.62305 10.873C9.49935 10.7493 9.4375 10.5964 9.4375 10.4141ZM12.2207 10.4141C12.2207 10.2318 12.2826 10.0788 12.4062 9.95508C12.5299 9.82812 12.6976 9.76465 12.9092 9.76465C13.124 9.76465 13.2917 9.82812 13.4121 9.95508C13.5358 10.0788 13.5977 10.2318 13.5977 10.4141C13.5977 10.5964 13.5358 10.7493 13.4121 10.873C13.2917 10.9967 13.124 11.0586 12.9092 11.0586C12.6976 11.0586 12.5299 10.9967 12.4062 10.873C12.2826 10.7493 12.2207 10.5964 12.2207 10.4141Z"
                                                fill="#737373"/>
                                        </svg>
                                        <span id="textStatus1${id}" > Disable</span>
                                    </button>`;

                        },
                        error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });

                }
            } else {
                if (confirm("Bạn có muốn bật hoạt động?")) {
                    // Chuyển hướng đến trang xử lý chỉnh sửa

                    $.ajax({
                        url: '/token/update-service', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                        method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                        dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                        data: {
                            id: id,
                            status: 10,
                            // Thêm các tham số khác nếu cần thiết
                        }, success: function (data) {
                            var cell4 = document.getElementById("btnstatus"+id);
                            cell4.innerHTML = `    <button type="button" class="btn myb14" id="clearbtnsctivity1${id}"
                                            onclick="confirmStatusActivityService(${id})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                            <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                        </svg>
                                        <span id="textStatus1${id}" >Enable</span>

                                    </button>`;
                        },
                        error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });
                }
            }

        }

        function chanTimeStamp(temp) {

            const formattedDateTime = " "
            if (temp != null) {
                // Timestamp ban đầu
                const timestamp = temp * 1000;

                // Tạo đối tượng Date từ timestamp
                const date = new Date(timestamp);

                const formattedDateTime = date.toLocaleString('vi-VN', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric'
                });

                return formattedDateTime
            }
            return formattedDateTime

        }

        function changeColor(key) {

            var span = document.getElementById("changeColor" + key);
            if (span.classList.contains("mtactive")) {

                span.classList.remove("mtactive");
                var tableRow = document.getElementById('endPointdataservice' + key);
                const cells = tableRow.querySelectorAll("tr");
                cells.forEach(cell => {
                    cell.parentNode.removeChild(cell);
                });
            } else {
                var tableRow = document.getElementById('endPointdataservice' + key);

                const cells = tableRow.querySelectorAll("tr");
                cells.forEach(cell => {
                    cell.parentNode.removeChild(cell);
                });
                span.classList.add("mtactive");
            }
        }

        function confirmStatusActivityEndPoint(id) {

            var span = document.getElementById("clearbtnsctivityendpont1" + id);
            var idText = document.getElementById("textStatusendpoint1" + id);
            if (idText.classList.contains('myb15')) {
                if (confirm("Bạn có muốn mở hoạt động?")) {
                    $.ajax({
                        url: '/token/update-transfer', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                        method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                        dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                        data: {
                            id: id, status: 10, // Thêm các tham số khác nếu cần thiết
                        }, success: function (data) {
                            span.innerHTML=`<button class="btn myb14 p-0" id="textStatusendpoint1${id}"
                                                onclick="confirmStatusActivityEndPoint(${id})">
                                            <i
                                               class="fa fa-play"
                                               aria-hidden="true"></i>
                                            Enable
                                        </button>`
                        }, error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });

                }
            } else {
                if (confirm("Bạn có muốn tắt hoạt động?")) {
                    // Chuyển hướng đến trang xử lý chỉnh sửa

                    $.ajax({
                        url: '/token/update-transfer', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                        method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                        dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                        data: {
                            id: id, status: 20, // Thêm các tham số khác nếu cần thiết
                        }, success: function (data) {
                            // Xử lý dữ liệu trả về thành công

                            $(document).ready(function () {
                                // Remove existing text and add new text
                                span.innerHTML=`<button class="btn myb15 p-0" id="textStatusendpoint1${id}"
                                                onclick="confirmStatusActivityEndPoint(${id})">
                                            <i
                                               class="fa fa-pause"
                                               aria-hidden="true"></i>
                                            Disable
                                        </button>`
                            });
                        }, error: function (xhr, status, error) {
                            // Xử lý khi có lỗi xảy ra
                            console.log(xhr.responseText);
                        }
                    });
                }
            }

        }

        function createNewKey(id) {

            $.ajax({
                url: '/token/new-key', // Thay thế đường dẫn bằng route Laravel thực tế của bạn
                method: 'GET', // Thay đổi phương thức HTTP theo yêu cầu của bạn (GET, POST, PUT, DELETE, vv.)
                dataType: 'json', // Kiểu dữ liệu trả về từ route (JSON, HTML, vv.)
                data: {
                    id: id,

                    // Thêm các tham số khác nếu cần thiết
                }, success: function (data) {
                    // Xử lý dữ liệu trả về thành công
                    $(document).ready(function () {

                        // Remove existing text and add new text
                        $('#idNewKey' + id).text(data.newPublicKey);
                        var time = chanTimeStamp(data.exp_newKey)
                        $('#idNewKeyExp' + id).text(time);
                        alert('Update Key EndPoint Thành Công')
                    });
                }, error: function (xhr, status, error) {
                    // Xử lý khi có lỗi xảy ra
                    console.log(xhr.responseText);
                }
            });
        }


    </script>

@endpush







