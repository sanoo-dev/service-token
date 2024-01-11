@php use App\Modules\Token\Helpers\Constants\ConstantDefine;use Carbon\Carbon; @endphp

@extends('token::layouts.master_token')

@push('stylesheet')
    <link href="{{ mix('css/bootstrap.min.css') }} " rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    @include('token::layouts.header', ['data' => $data ?? null])

    <main class="container mymain">
        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb ">
                <li class="breadcrumb-item"><a href="#">Endpoint</a></li>
                <li class="breadcrumb-item " aria-current="page">Danh sách Endpoint</li>
            </ol>
        </nav>

        @include('token::endpoints.layouts.header')

        <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
            <div>
                <table class="table table-xs" id="mytable">
                    @include('token::endpoints.layouts.header-table')

                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $index => $item)
                            <tr id="changeColor{{ $index }}" class="h-100 ">
                                <th class="align-middle mcl1 accordion-button mlevel1 collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="true" aria-controls="collapse{{ $index }}" scope="row"
                                    onclick="changeColor({{ $index }}); loadEndpointDetail({{ $index }}, {{ $item['db_id'] }});  loadDataService({{ $index }},'{{ $item['domain'] }}', '{{ $item['server_ip'] }}')">
                                    {{ $item['name'] ?? null }}
                                </th>
                                <td class="align-middle">{{ $item['domain'] }}</td>
                                <td class="align-middle">{{ $item['server_ip'] }}</td>
                                @if($item['status'] == ConstantDefine::ACTIVITY)
                                    <td id="clearbtnsctivityendpont1{{ $item['db_id'] }}"
                                        class="align-middle text-center text-success">
                                        <button class="btn myb14 " id="textStatusendpoint1{{ $item['db_id'] }}"
                                                onclick="confirmStatusActivityEndpoint({{ $item['db_id'] }})">
                                            <i class="fa fa-play"></i>
                                            Enable
                                        </button>
                                    </td>
                                @else
                                    <td id="clearbtnsctivityendpont1{{ $item['db_id'] }}" class="align-middle">
                                        <button class="btn myb15 p-0" id="textStatusendpoint1{{ $item['db_id'] }}"
                                                onclick="confirmStatusActivityEndpoint({{ $item['db_id'] }})">
                                            <i class="fa fa-pause" aria-hidden="true"></i>
                                            Disable
                                        </button>
                                    </td>
                                @endif
                                <td class="align-middle text-center">{{ !empty($item['created_at']) ? Carbon::createFromTimestamp($item['created_at'])->format('d/m/Y') : '' }}</td>
                            </tr>

                            <tr class="hide-table-padding">
                                <td colspan="8" class="ps-0 pe-0">
                                    <div id="collapse{{ $index }}" class="collapse in p-3">
                                        <div class="accordion mypanel" id="accordionExample">
                                            <div class="accordion-item">
                                                <div class="accordion-header d-flex justify-content-between"
                                                     id="headingOne">
                                                    <button class="accordion-button" href="#" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $index }}Child"
                                                            aria-expanded="true"
                                                            aria-controls="collapse{{ $index }}Child">
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
                                                <div id="collapse{{ $index }}Child"
                                                     class="accordion-collapse collapse show"
                                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <table class="table table-xs mytable1">
                                                            <thead>
                                                            <tr class="mt-3">
                                                                <th scope="col">Key</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="endpoint_key_{{ $index }}"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="accordion-header d-flex justify-content-between"
                                                     id="headingOne">
                                                    <button class="accordion-button" href="#" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $index }}2Child"
                                                            aria-expanded="true"
                                                            aria-controls="collapse{{ $index }}2Child">
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
                                                            <li>
                                                                <a class="dropdown-item" href="#">
                                                                    Something else here
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="collapse{{ $index }}2Child"
                                                     class="accordion-collapse collapse show"
                                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <table class="table table-xs mytable1">
                                                            <thead>
                                                            <tr class="mt-3">
                                                                <th scope="col">Tên Service</th>
                                                                <th scope="col">Domain</th>
                                                                <th scope="col">IP</th>
                                                                <th scope="col">Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="endpoint_services_{{ $index }}"></tbody>
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
                                    @foreach($data as $index => $item)
                                        <table class="table mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="tb1">Tên Endpoint</td>
                                                <td class="tb2">{{ $item['name'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="tb1">Domain</td>
                                                <td class="tb2">{{ $item['domain'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="tb1">IP</td>
                                                <td class="tb2">{{ $item['server_ip'] }}</td>
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
                @include('token::endpoints.popup.create-endpoint-modal')

                @if(!empty($data))
                    {{ $data->links('vendor.pagination.custom') }}
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
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $(".toggle-password-login").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            const input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                document.getElementById('password-field-login').setAttribute('type', 'text');
            } else {
                document.getElementById('password-field-login').setAttribute('type', 'password');
            }
        });
        $(".flatpickr-input").flatpickr({
            enableTime: true, dateFormat: "d-m-Y H:i",
        });

        // Endpoint
        function loadEndpointDetail(index, id) {
            $.ajax({
                url: '/api/endpoints/' + id,
                method: 'GET',
                contentType: 'application/json',
                success: function (response) {
                    if (response.status === 100) {
                        const html = renderHtmlEndpointDetail(response.data);
                        $('#endpoint_key_' + index).html(html);
                    } else {
                        console.log(response.message || 'Lỗi chưa xác định.');
                    }
                },
                error: function (error) {
                    console.log(error.statusText);
                }
            });
        }

        function renderHtmlEndpointDetail(item) {
            if (typeof item === 'object' && item !== null) {
                return `
                    <tr>
                        <td id="oldkey${item.db_id}" style="overflow-x:hidden; white-space: nowrap;width:100px; max-width:600px; vertical-align: inherit;text-overflow: ellipsis;">${item.public_key}</td>
                        <td><i style="width:30px; max-width:20px;cursor: pointer;" class="fa fa-clipboard" onclick="copyText(0)"></i></td>
                    </tr>
                `;
            } else {
                return `<tr><td style="overflow-x:hidden; white-space: nowrap;width:50px; max-width:350px; vertical-align: inherit;text-overflow: ellipsis;"></td></tr>`;
            }
        }

        function copyText(key) {
            const row = event.target.parentNode.parentNode;
            const textToCopy = row.cells[key].innerText;
            const tempInput = document.createElement("input")

            tempInput.value = textToCopy;
            document.body.appendChild(tempInput)
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);

            Swal.fire({
                icon: 'success',
                title: 'Thông báo',
                text: "Đã copy thành công!"
            })
        }

        function confirmStatusActivityEndpoint(id) {
            const span = document.getElementById("clearbtnsctivityendpont1" + id);
            const idText = document.getElementById("textStatusendpoint1" + id);
            if (idText.classList.contains('myb15')) {
                if (confirm("Bạn có muốn mở hoạt động?")) {
                    $.ajax({
                        url: '/api/endpoints/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 10
                        }, success: function (response) {
                            if (response.status === 100) {
                                span.innerHTML = `
                                    <button class="btn myb14 p-0" id="textStatusendpoint1${id}"
                                        onclick="confirmStatusActivityEndpoint(${id})">
                                        <i class="fa fa-play" aria-hidden="true"></i>
                                        Enable
                                    </button>`
                            } else {
                                console.log(response.message || 'Lỗi chưa xác định.');
                            }
                        }, error: function (error) {
                            console.log(error.statusText);
                        }
                    });
                }
            } else {
                if (confirm("Bạn có muốn tắt hoạt động?")) {
                    $.ajax({
                        url: '/api/endpoints/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 20
                        }, success: function (response) {
                            $(document).ready(function () {
                                if (response.status === 100) {
                                    span.innerHTML = `
                                        <button class="btn myb15 p-0" id="textStatusendpoint1${id}"
                                            onclick="confirmStatusActivityEndpoint(${id})">
                                                <i class="fa fa-pause" aria-hidden="true"></i>
                                                Disable
                                        </button>`
                                } else {
                                    console.log(response.message || 'Lỗi chưa xác định.');
                                }
                            });
                        }, error: function (error) {
                            console.log(error.statusText);
                        }
                    });
                }
            }
        }

        // Service
        function loadDataService(key, endpointDomain, serverIp) {
            $.ajax({
                url: '/api/services/list?endpoint_domain=' + endpointDomain + '&endpoint_server_ip=' + serverIp,
                method: 'GET',
                contentType: 'application/json',
                success: function (response) {
                    if (response.status === 100) {
                        renderHtmlService(response.data, key);
                    } else {
                        console.log(response.message || 'Lỗi chưa xác định.');
                    }
                },
                error: function (error) {
                    console.log(error.statusText);
                }
            });
        }

        function renderHtmlService(data, key) {
            let temp = '';
            if (data !== "") {
                const tableRow = document.getElementById('endpoint_services_' + key);
                data.forEach(item => {
                    const keys = Object.keys(item);
                    const row = document.createElement('tr');
                    const cell1 = document.createElement('td');
                    cell1.textContent = item.name;
                    const cell2 = document.createElement('td');
                    cell2.textContent = item.domain;
                    const cell3 = document.createElement('td');
                    cell3.textContent = item.server_ip;
                    const cell4 = document.createElement('td');
                    cell4.setAttribute('id', 'btnstatus' + item.db_id);
                    if (parseInt(item.status) === 10) {
                        cell4.innerHTML = `
                            <button type="button" class="btn myb14" id="clearbtnsctivity1${item.db_id}"
                                onclick="confirmStatusActivityService(${item.db_id})">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                    <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                </svg>
                                <span id="textStatus1${item.db_id}" >Enable</span>
                            </button>`;
                    } else {
                        cell4.innerHTML = `
                            <button type="button" class="btn myb15" id="clearbtnsctivity1${item.db_id}"
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
                });
            } else {
                temp = `<tr><td style="overflow-x:hidden; white-space: nowrap;width:50px; max-width:350px; vertical-align: inherit;text-overflow: ellipsis;"></td></tr>`
            }
            return temp
        }

        function confirmStatusActivityService(id) {
            const span = document.getElementById("clearbtnsctivity1" + id);
            if (span.classList.contains('myb14')) {
                if (confirm("Bạn có muốn tắt hoạt động?")) {
                    $.ajax({
                        url: '/api/services/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 20,
                        }, success: function (response) {
                            if (response.status === 100) {
                                const cell4 = document.getElementById("btnstatus" + id);
                                cell4.innerHTML = `
                                    <button type="button" class="btn myb15" id="clearbtnsctivity1${id}"
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
                            } else {
                                console.log(response.message || 'Lỗi chưa xác định.');
                            }
                        }, error: function (error) {
                            console.log(error.statusText);
                        }
                    });

                }
            } else {
                if (confirm("Bạn có muốn bật hoạt động?")) {
                    $.ajax({
                        url: '/api/services/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 10,
                        }, success: function (response) {
                            if (response.status === 100) {
                                const cell4 = document.getElementById("btnstatus" + id);
                                cell4.innerHTML = `
                                <button type="button" class="btn myb14" id="clearbtnsctivity1${id}"
                                    onclick="confirmStatusActivityService(${id})">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                        <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                    </svg>
                                    <span id="textStatus1${id}" >Enable</span>
                                </button>`;
                            } else {
                                console.log(response.message || 'Lỗi chưa xác định.');
                            }
                        }, error: function (error) {
                            console.log(error.statusText);
                        }
                    });
                }
            }
        }

        // Common
        function changeColor(key) {
            let tableRow;
            const span = document.getElementById("changeColor" + key);
            if (span.classList.contains("mtactive")) {
                span.classList.remove("mtactive");
                tableRow = document.getElementById('endpoint_services_' + key);
                const cells = tableRow.querySelectorAll("tr");
                cells.forEach(cell => {
                    cell.parentNode.removeChild(cell);
                });
            } else {
                tableRow = document.getElementById('endpoint_services_' + key);

                const cells = tableRow.querySelectorAll("tr");
                cells.forEach(cell => {
                    cell.parentNode.removeChild(cell);
                });
                span.classList.add("mtactive");
            }
        }
    </script>
@endpush
