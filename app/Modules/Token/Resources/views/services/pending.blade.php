@extends('token::layouts.master_token')

@push('stylesheet')
    <link href="{{ mix('css/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@section('title', 'CMS Token')
@section('content')
    @php
        $headerName = 'Danh sách Service chờ duyệt';
        $checkLast = 1;
        $routeName = route('services.pending');
    @endphp
    @include('token::layouts.header')
    <main class="container mymain">
        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb ">
                <li class="breadcrumb-item"><a href="#">Services</a></li>
                <li class="breadcrumb-item " aria-current="page">Danh sách Service chờ duyệt</li>
            </ol>
        </nav>
        @include('token::services.layout.header', ['routeName' => $routeName, 'headerName' => $headerName])
        <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
            <div>
                <table class="table table-xs" id="mytable">
                    @include('token::services.layout.header_table', ['checkLast'=> $checkLast])
                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $index => $item)
                            <tr id="btn-open-detail">
                                <td></td>
                                <th scope="row" data-bs-toggle="modal"
                                    data-bs-target="#formDetail">{{ $item['name'] ?? null }}</th>
                                <td>{{$item['domain']}}</td>
                                <td>{{$item['server_ip']}}</td>
                                <td>{{$item['endpoint_server_ip']}}</td>
                                <td>{{$item['endpoint_domain']}}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('services.accept', ['id' => $item['db_id']]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm mybt3"
                                                title="Duyệt" id="btn_confirm"
                                                onclick="return confirm('Bạn chắc chắn muốn duyệt?')">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        </button>
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
                            @include('token::services.popup.create-service-modal')
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
@endpush
