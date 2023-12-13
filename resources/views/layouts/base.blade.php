<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{mix('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{mix('css/style.css')}}" rel="stylesheet">
    <link href="{{mix('css/screen.css')}}" rel="stylesheet">
    <link href="{{mix('css/custom.css')}}" rel="stylesheet">
    <!-- <link href="css/screen.css" rel="stylesheet"> -->
{{--    <link href="{{mix('css/darkmode.css')}}" rel="stylesheet">--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Include SweetAlert2 CSS and JS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Service Token</title>
</head>
<body class="bgmain">
@yield('master')
@if(Session::has('success'))
    <div
        class="position-fixed bottom-0 end-0 mb-5 me-2 toast align-items-center text-white bg-success border-0 notification-init-alert"
        role="alert"
        aria-live="assertive"
        aria-atomic="true" style="z-index: 11">
        <div class="d-flex">
            <div class="toast-body">
                {{Session::get('success') ?? 'Action success'}}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
@endif
@if(Session::has('error'))
    <div
        class="position-fixed bottom-0 end-0 mb-5 me-2 toast align-items-center text-white bg-success border-0 notification-init-alert"
        role="alert"
        aria-live="assertive"
        aria-atomic="true" style="z-index: 11">
        <div class="d-flex">
            <div class="toast-body">
                {{Session::get('error') ?? 'Something went wrong'}}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
@endif
<script src="{{mix('js/jquery.js')}}"></script>
<script src="{{mix('js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
@stack('scripts')


<script>
    // Lấy thông báo từ Session (sử dụng Laravel)
    var alertMessage = "{{ Session::get('alert') }}";
    // Kiểm tra xem có thông báo hay không
    if (alertMessage) {
        // Hiển thị thông báo dạng popup với SweetAlert2
        Swal.fire({
            icon: 'info',
            title: 'Thông báo',
            text: alertMessage
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
<script>
    $( "#includeHeader" ).load( "header.html", function() {
        alert( "Load was performed." );
    });
    // $("#includeHeader").load("header.html");
</script>
</html>
