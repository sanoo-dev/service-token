@extends('token::layouts.master-token')

@push('stylesheet')
    <link href="{{mix('css/bootstrap.min.css')}} " rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    @php
        $customData = ['name' => 'Danh Sách Services Chờ Duyệt','checkLast'=>1];
    @endphp
    @include('token::layouts.header',['nameView'=>$customData])
    <main class="container mymain">
        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb ">
                <li class="breadcrumb-item"><a href="#">Services</a></li>
                <li class="breadcrumb-item " aria-current="page">Danh Sách Services Chờ Duyệt</li>
            </ol>
        </nav>
        @include('token::services.layout.header',['viewName'=>'viewAcceptService'])
        <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">


            <div>
                <table class="table table-xs" id="mytable">
                    @include('token::services.layout.header_table',['checkLast'=>$customData])
                    <tbody>

                    @if(!empty($data))

                        @foreach($data as $key=>$item)

                            <tr id="btn-open-detail">
                                <td></td>
                                <th scope="row" data-bs-toggle="modal"
                                    data-bs-target="#formDetail">{{$item['appName']??null}}</th>

                                <td>{{$item['domain']}}</td>
                                <td>{{$item['serveIp']}}</td>
                                <td>{{$item['serveIpTransfer']}}</td>
                                <td>{{$item['domainTransfer']}}</td>
                                <td>
                                    <form method="POST" action="{{route('acceptService')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$item['db_id']}}">
                                        <button type="submit" class="btn btn-sm mybt3" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Duyệt" id="btn-confirm" data-bs-toggle="modal"
                                                data-bs-target="#confirm"><i class="fa fa-check-circle"
                                                                             aria-hidden="true"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    @endif
                    </tbody>
                </table>
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
                            @include('token::services.popup.create_service')
                        </div>
                    </div>
                </div>
                @if(!empty($data))
                    {{ $data->links('vendor.pagination.custom')}}
                @endif

            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy thông báo từ Session (sử dụng Laravel)
            var alertMessage = '{{!empty($code)?$code:'' }}';
            console.log("{{!empty($message)?$message:'' }}")
            // Kiểm tra xem có thông báo hay không
            if (alertMessage==='100') {
                // Hiển thị thông báo dạng popup với SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Thông báo',
                    text: "{{!empty($message)?$message:'' }}"
                })
            }
            if (alertMessage==='42'){
                // Hiển thị thông báo dạng popup với SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Thông báo',
                    text: "{{!empty($message)?$message:'' }}"
                })
            }
        });
    </script>
@endpush







