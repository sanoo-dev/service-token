@extends('layouts.base')

@section('master')

    <body class="bgmain">

    @yield('content')
    </body>
@push('scripts')
    <script>
        $( "#includeHeader" ).load( "header.html", function() {
            alert( "Load was performed." );
        });

        document.addEventListener('DOMContentLoaded', function () {
            @if(session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: "{{ session()->get('success') }}"
            })
            @elseif(session()->has('error'))
            Swal.fire({
                icon: 'fail',
                title: 'Thất bại',
                text: "{{ session()->get('error') }}"
            })
            @elseif($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: "{{ $errors->all()[0] }}"
            })
            @endif
        });
        // $("#includeHeader").load("header.html");
    </script>
@endpush
@stop
