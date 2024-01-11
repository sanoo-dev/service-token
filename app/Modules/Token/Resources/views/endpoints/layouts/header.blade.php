<div class="d-flex justify-content-between">
    <div class="myf6">{{ $customData['name'] ?? 'Danh sách Endpoint'}}</div>
    <div class="d-flex justify-content-between mmm2">
        <button type="button" class="btn myb1 myb11" id="btn-add-detail" data-bs-toggle="modal"
                data-bs-target="#addDetail">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.8621 6L12.8621 18" stroke="#333333" stroke-linecap="round"/>
                <path d="M18.8621 12L6.86206 12" stroke="#333333" stroke-linecap="round"/>
            </svg>
            &nbsp;&nbsp;Thêm Endpoint
        </button>
    </div>
</div>
<form action="{{ route('endpoints.index') }}">
    <section id="main-table-component" class="border-end border-bottom border-start shadow-sm">
        @csrf
        <div class="d-flex justify-content-between myd2">
            <div class="row my-auto ms-3 myd1">
                <div class="col-2 position-relative my-hover">
                    <input type="text" id="t1" name="s_name" placeholder="Tên Endpoint" class="form-control minp1"
                           aria-describedby="text1" value="{{ request()->get('s_name') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control minp1" name="s_domain" placeholder="Domain"
                           aria-label="State" value="{{ request()->get('s_domain') ?? ''}}">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control minp1" name="s_server_ip" placeholder="IP" aria-label="Zip"
                           value="{{ request()->get('s_server_ip') ?? ''}}">
                </div>
            </div>
            <div class="my-auto">
                <button type="submit" class="btn mybt2 me-4">Tìm kiếm</button>
            </div>
        </div>
    </section>
</form>
