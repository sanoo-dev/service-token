    <header class="mheader">
    <section class="container d-flex flex-column flex-md-row align-items-center pb-3 mb-4">
        <a href="{{ env('URL_TOKEN') }}" class="d-flex align-items-center text-dark text-decoration-none">
            <span class="fs-4 myl1">Service Token</span>
        </a>
        <div class="d-inline-flex mt-2 mt-md-0 ms-md-auto align-items-center">
            @if($_SERVER['REQUEST_URI'] == "/token/services" || $_SERVER['REQUEST_URI'] == "/token/services/pending")
                <div class="dropdown-center mynav1 active ">
                    <p class="text-center mt-2 mb-0"><img src="{{ mix('img/logo_service.png') }}"/></p>
                    <a class="dropdown-toggle btn" href="#" role="button" id="dropdownMenuLink"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        SERVICE
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <span class="dropdown-menu-arrow"></span>
                        <li><a class="dropdown-item" href="{{ route('services.pending') }}">Chờ Duyệt</a></li>

                        <li><a class="dropdown-item" href="{{ route('services.index') }}">Danh Sách</a></li>
                    </ul>
                </div>
            @else
                <div class="dropdown-center mynav1  ">
                    <p class="text-center mt-2 mb-0"><img src="{{mix('img/logo_service01.png')}}"/></p>
                    <a class="dropdown-toggle btn" href="#" role="button" id="dropdownMenuLink"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        SERVICE
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <span class="dropdown-menu-arrow"></span>
                        <li><a class="dropdown-item" href="{{ route('services.pending') }}">Chờ Duyệt</a></li>

                        <li><a class="dropdown-item" href="{{ route('services.index') }}">Danh Sách</a></li>
                    </ul>
                </div>
            @endif
            @if($_SERVER['REQUEST_URI'] == "/token/endpoints")
                <div id="header-endpoint" class="dropdown-center mynav1 active">
                    <p class="text-center mt-2 mb-0"><img src="{{ mix('img/logo_service2.png') }}"/></p>
                    <a class="dropdown-toggle btn" href="#" role="button" id="dropdownMenuLink"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        ENDPOINT
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <span class="dropdown-menu-arrow"></span>
                        <li><a class="dropdown-item" href="{{ route('endpoints.index') }}">Danh Sách</a></li>
                    </ul>
                </div>
            @else
                <div id="header-endpoint" class="dropdown-center mynav1">
                    <p class="text-center mt-2 mb-0"><img src="{{ mix('img/logo_setting.png') }}"/></p>
                    <a class="dropdown-toggle btn" href="#" role="button" id="dropdownMenuLink"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        ENDPOINT
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <span class="dropdown-menu-arrow"></span>
                        <li><a class="dropdown-item" href="{{ route('endpoints.index') }}">Danh Sách</a></li>
                    </ul>
                </div>
            @endif
            <img src="{{ mix('img/Rect.png') }}" class="me-3 ms-3"/>
            <div class="dropdown-center">
                <a class="dropdown-toggle btn mb-2 myinfo" href="#" role="button" id="dropdownMenuLink"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <p id="nameinfo" style="font-size: 60%">HK</p>
                </a>
                <ul class="dropdown-menu mmm1" aria-labelledby="dropdownMenuLink">
                    <span class="dropdown-menu-arrow"></span>
                    <li>
                        <a class="dropdown-item myf5" href="{{ env('URL_ERP', 'https://erp.tuoitre.vn') }}">
                            <i class="fa fa-user-circle-o"></i>&nbsp;Thông tin cá nhân
                        </a>
                    </li>
                    <li>
                        <button
                            style="background-color: transparent;border: none;padding: 0; margin: 0;font: inherit;cursor: pointer;outline: none;"
                            id="clearCookieButton">
                            <a class="dropdown-item myf5">
                                <i class="fa fa-sign-out"></i>&nbsp;Đăng xuất
                            </a>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <!--
        END MAIN MENU
        -->
    </section>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function để lấy giá trị của cookie
    function getCookieValue(cookieName) {
        var name = cookieName + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var cookieArray = decodedCookie.split(';');
        // Lặp qua từng phần tử trong mảng cookieArray để tìm cookie có tên tương ứng
        for (var i = 0; i < cookieArray.length; i++) {
            var cookie = cookieArray[i];

            // Xóa khoảng trắng ở đầu chuỗi
            while (cookie.charAt(0) == ' ') {
                cookie = cookie.substring(1);
            }

            // Nếu tìm thấy cookie có tên tương ứng
            if (cookie.indexOf(name) == 0) {
                return cookie.substring(name.length, cookie.length);
            }
        }
        return "";
    }

    // Gọi function getCookieValue() với tên của cookie bạn muốn lấy giá trị
    var valueOfCookie = getCookieValue('_info');

    // Hiển thị giá trị của cookie trong phần tử HTML
    document.getElementById("nameinfo").innerHTML = valueOfCookie;

    $(document).ready(function () {
        $('#clearCookieButton').click(function () {
            clearCookie('_ttoauth_prod');
            // Gửi người dùng đến trang đăng nhập hoặc trang khác sau khi đăng xuất
            // window.location.href = 'trang-dang-nhap.html';
        });

        function clearCookie(cookieName) {
            document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;domain=.tuoitre.vn';
            window.location.reload();

        }
    });
</script>
