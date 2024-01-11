<div class="modal my-modal fade" id="addDetail" tabindex="-1" aria-labelledby="addModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title my-title" id="passModalLabel">THÊM ENDPOINT</h5>
                <button type="button" class="btn-close mybt4" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form row" id="form-name" action="{{ route('endpoints.store') }}"
                      method="post" data-url-login-by-password="#" accept-charset="UTF-8">
                    @csrf
                    <label class="col-12 pt-3 position-relative my-hover">
                        <label class="position-absolute a2">Tên Endpoint</label>
                        <input type="text" id="t1" name="name" class="form-control myinput2" required>
                    </label>

                    <label class="col-12 pt-3 position-relative my-hover">
                        <label class="position-absolute a2">Server IP</label>
                        <input type="text" id="t1" name="server_ip" class="form-control myinput2" required>
                    </label>

                    <label class="col-12 pt-3 position-relative my-hover">
                        <label class="position-absolute a2">Domain</label>
                        <input type="text" id="t1" name="domain" class="form-control myinput2" required>
                    </label>

                    <div class="col-12 text-center mt-3">
                        <button type="button" class="btn mybt6 fw-bolder" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn ps-3 pe-3 btn-outline-secondary">Tạo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
