<div class="d-flex justify-content-between">
    <div class="myf6">{{ $headerName ?? 'Danh sách Service' }}</div>
    <div class="d-flex justify-content-between mmm2">
        <button type="button" class="btn myb1 myb11" id="btn-add-detail" data-bs-toggle="modal"
                data-bs-target="#addDetail">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.8621 6L12.8621 18" stroke="#333333" stroke-linecap="round"/>
                <path d="M18.8621 12L6.86206 12" stroke="#333333" stroke-linecap="round"/>
            </svg>
            &nbsp;&nbsp;
            Thêm Services
        </button>
    </div>
</div>
<form action="{{ $routeName ?? '' }}">
    <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
        <div class="d-flex justify-content-between  myd2">
            <div class="row my-auto ms-3 myd1">
                <div class="col-2 position-relative my-hover">
                    <input type="text" id="t1" name="s_name" placeholder="Tên Service" class="form-control minp1"
                           value="{{ request()->get('s_name') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" name="s_domain" class="form-control minp1" placeholder="Domain" value="{{ request()->get('s_domain') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" name="s_server_ip" class="form-control minp1" placeholder="IP" value="{{ request()->get('s_server_ip') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" name="s_endpoint_server_ip" class="form-control minp1" placeholder="Endpoint IP"
                           value="{{ request()->get('s_endpoint_server_ip') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" name="s_endpoint_domain" class="form-control minp1" placeholder="Endpoint Domain"
                           value="{{ request()->get('s_endpoint_domain') ?? ''}}">
                </div>
            </div>
            <div class="my-auto">
                <button type="submit" class="btn mybt2 me-4">Tìm kiếm</button>
            </div>
        </div>
    </section>
</form>
