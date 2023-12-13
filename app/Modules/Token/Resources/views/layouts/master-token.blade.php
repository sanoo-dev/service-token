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
        // $("#includeHeader").load("header.html");
    </script>
@endpush
@stop



