@php use App\Modules\Token\Helpers\Constants\ConstantDefine; @endphp
@extends('token::layouts.master_token')

@push('stylesheet')
    <link href="{{ mix('css/bootstrap.min.css')}} " rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    @php
        $customData = ['name' => 'Danh sách Service ', 'checkLast' => 3];
    @endphp
    @include('token::layouts.header', ['routeName' => route('services.index')])
    <main class="container mymain">
        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb ">
                <li class="breadcrumb-item"><a href="#">Services</a></li>
                <li class="breadcrumb-item " aria-current="page">Danh sách Service</li>
            </ol>
        </nav>
        @include('token::services.layout.header')
        <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
            <div>
                <table class="table table-xs h-100" id="mytable">
                    @include('token::services.layout.header_table', ['checkLast' => $customData])
                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $index => $item)
                            <tr id="btn-open-detail1">
                                <td></td>
                                <th id="onClickDetailService" onclick="loadServiceDetail({{ $item['db_id'] }})"
                                    scope="row"
                                    data-bs-toggle="modal"
                                    data-bs-target="#formDetail">{{ $item['name'] ?? null }}</th>
                                <td>{{ $item['domain'] ?? null }}</td>
                                <td>{{ $item['server_ip'] }}</td>
                                <td>{{ $item['endpoint_server_ip'] }}</td>
                                <td>{{ $item['endpoint_domain'] }}</td>
                                @if($item['status'] == ConstantDefine::ACTIVITY)
                                    <td class="align-middle text-center text-success"
                                        id="clearbtnsctivity1{{ $item['db_id'] }}">

                                        <button type="button" class="btn myb14"
                                                id="checkbtnsctivity1{{ $item['db_id'] }}"
                                                onclick="confirmStatusActivityService({{ $item['db_id'] }})">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                                <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                            </svg>
                                            <span id="textStatus1{{ $item['db_id'] }}" class="">Enable</span>

                                        </button>
                                    </td>
                                @else
                                    <td class="align-middle text-center text-success "
                                        id="clearbtnsctivity1{{ $item['db_id'] }}">
                                        <button type="button" class="btn myb15"
                                                id="checkbtnsctivity1{{ $item['db_id'] }}"
                                                onclick="confirmStatusActivityService({{ $item['db_id'] }})">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="10" cy="10" r="7.5" stroke="#979797"/>
                                                <path
                                                    d="M6.6543 10.4141C6.6543 10.2318 6.71615 10.0788 6.83984 9.95508C6.96354 9.82812 7.13118 9.76465 7.34277 9.76465C7.55762 9.76465 7.72526 9.82812 7.8457 9.95508C7.9694 10.0788 8.03125 10.2318 8.03125 10.4141C8.03125 10.5964 7.9694 10.7493 7.8457 10.873C7.72526 10.9967 7.55762 11.0586 7.34277 11.0586C7.13118 11.0586 6.96354 10.9967 6.83984 10.873C6.71615 10.7493 6.6543 10.5964 6.6543 10.4141ZM9.4375 10.4141C9.4375 10.2318 9.49935 10.0788 9.62305 9.95508C9.74674 9.82812 9.91439 9.76465 10.126 9.76465C10.3408 9.76465 10.5085 9.82812 10.6289 9.95508C10.7526 10.0788 10.8145 10.2318 10.8145 10.4141C10.8145 10.5964 10.7526 10.7493 10.6289 10.873C10.5085 10.9967 10.3408 11.0586 10.126 11.0586C9.91439 11.0586 9.74674 10.9967 9.62305 10.873C9.49935 10.7493 9.4375 10.5964 9.4375 10.4141ZM12.2207 10.4141C12.2207 10.2318 12.2826 10.0788 12.4062 9.95508C12.5299 9.82812 12.6976 9.76465 12.9092 9.76465C13.124 9.76465 13.2917 9.82812 13.4121 9.95508C13.5358 10.0788 13.5977 10.2318 13.5977 10.4141C13.5977 10.5964 13.5358 10.7493 13.4121 10.873C13.2917 10.9967 13.124 11.0586 12.9092 11.0586C12.6976 11.0586 12.5299 10.9967 12.4062 10.873C12.2826 10.7493 12.2207 10.5964 12.2207 10.4141Z"
                                                    fill="#737373"/>
                                            </svg>
                                            <span id="textStatus1{{ $item['db_id'] }}" class=""> Disable</span>
                                        </button>
                                    </td>
                                @endif

                                <td class="align-middle text-center">{{ !empty($item['created_at'])?\Carbon\Carbon::createFromTimestamp($item['created_at'])->format('d/m/Y'):'' }}</td>
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
                                <h5 class="modal-title my-title" id="passModalLabel">CHI TIẾT SERVICE</h5>
                                <button type="button" class="btn-close mybt4" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table id="service_detail" class="table mb-0">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ############################################################################ -->
                <div class="modal my-modal fade" id="addDetail" tabindex="-1" aria-labelledby="addModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title my-title" id="passModalLabel">THÊM SERVICE</h5>
                                <button type="button" class="btn-close mybt4" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            @include('token::services.popup.create-service-modal')
                        </div>
                    </div>
                </div>

                @if(!empty($data))
                    {{ $data->links('vendor.pagination.custom') }}
                @endif
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        function confirmStatusActivityService(id) {
            const span = document.getElementById("clearbtnsctivity1" + id);
            const idText = document.getElementById("checkbtnsctivity1" + id);
            if (idText.classList.contains('myb15')) {
                if (confirm("Bạn có muốn mở hoạt động?")) {
                    $.ajax({
                        url: '/api/services/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 10,
                        }, success: function (response) {
                            if (response.status === 100) {
                                span.innerHTML = `
                                <button type="button" class="btn myb14" id="checkbtnsctivity1${id}"
                                        onclick="confirmStatusActivityService(${id})">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="7.5" stroke="#219653"/>
                                        <path d="M6.66665 10L9.16665 12.5L13.3333 7.5" stroke="#219653"/>
                                    </svg>
                                    <span id="textStatus1"${id} class="">Enable</span>
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
                        url: '/api/services/' + id,
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            status: 20,
                        }, success: function (response) {
                            if (response.status === 100) {
                                span.innerHTML = `
                                    <button type="button" class="btn myb15" id="checkbtnsctivity1${id}"
                                        onclick="confirmStatusActivityService(${id})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="7.5" stroke="#979797"/>
                                            <path
                                                d="M6.6543 10.4141C6.6543 10.2318 6.71615 10.0788 6.83984 9.95508C6.96354 9.82812 7.13118 9.76465 7.34277 9.76465C7.55762 9.76465 7.72526 9.82812 7.8457 9.95508C7.9694 10.0788 8.03125 10.2318 8.03125 10.4141C8.03125 10.5964 7.9694 10.7493 7.8457 10.873C7.72526 10.9967 7.55762 11.0586 7.34277 11.0586C7.13118 11.0586 6.96354 10.9967 6.83984 10.873C6.71615 10.7493 6.6543 10.5964 6.6543 10.4141ZM9.4375 10.4141C9.4375 10.2318 9.49935 10.0788 9.62305 9.95508C9.74674 9.82812 9.91439 9.76465 10.126 9.76465C10.3408 9.76465 10.5085 9.82812 10.6289 9.95508C10.7526 10.0788 10.8145 10.2318 10.8145 10.4141C10.8145 10.5964 10.7526 10.7493 10.6289 10.873C10.5085 10.9967 10.3408 11.0586 10.126 11.0586C9.91439 11.0586 9.74674 10.9967 9.62305 10.873C9.49935 10.7493 9.4375 10.5964 9.4375 10.4141ZM12.2207 10.4141C12.2207 10.2318 12.2826 10.0788 12.4062 9.95508C12.5299 9.82812 12.6976 9.76465 12.9092 9.76465C13.124 9.76465 13.2917 9.82812 13.4121 9.95508C13.5358 10.0788 13.5977 10.2318 13.5977 10.4141C13.5977 10.5964 13.5358 10.7493 13.4121 10.873C13.2917 10.9967 13.124 11.0586 12.9092 11.0586C12.6976 11.0586 12.5299 10.9967 12.4062 10.873C12.2826 10.7493 12.2207 10.5964 12.2207 10.4141Z"
                                                fill="#737373"/>
                                        </svg>
                                        <span id="textStatus1"${id} class=""> Disable</span>
                                    </button>`
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

        function loadServiceDetail(id) {
            $.ajax({
                url: "/api/services/" + id,
                method: 'GET',
                contentType: 'application/json',
                success: function (response) {
                    if (response.status === 100) {
                        const html = renderHtml(response.data);
                        $('#service_detail').html(html);
                    } else {
                        console.log(response.message || 'Lỗi chưa xác định.');
                    }
                },
                error: function (error) {
                    console.log(error.statusText);
                }
            });
        }

        function renderHtml(item) {
            if (typeof item === 'object' && item !== null) {
                return `
                    <tbody>
                        <tr>
                            <td class="tb1"> Tên endpoint</td>
                            <td class="tb2"> ${item.name}</td>
                        </tr>
                        <tr>
                            <td class="tb1"> Secret Key</td>
                            <td class="tb2">${item.secret_key}</td>
                        </tr>
                        <tr>
                            <td class="tb1"> Partner Code</td>
                            <td class="tb2">${item.partner_code}</td>
                        </tr>
                        <tr>
                            <td class="tb1"> Domain</td>
                            <td class="tb2">${item.domain}</td>
                        </tr>
                        <tr>
                            <td class="tb1"> IP</td>
                            <td class="tb2">${item.server_ip}</td>
                        </tr>
                    </tbody>
                `
            }
            return '';
        }
    </script>
@endpush
