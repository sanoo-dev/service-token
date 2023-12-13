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
                <form class="form row" id="form-name" action="{{route('createEndPoint')}}"
                      method="post" data-url-login-by-password="#" accept-charset="UTF-8">
                    @csrf
                    <label class="col-12 pt-3 position-relative my-hover">
                        <label class="position-absolute a2">Tên ENDPOINT</label>
                        <input type="text" id="t1" name="name_endpoint"
                               class="form-control myinput2" aria-describedby="text1" required>
                    </label>
                    <label class="col-12 pt-3 my-hover">
                        <input type="text" name="serveip" class="form-control"
                               placeholder="Service IP" aria-label="State" required>
                    </label>
                    <label class="col-12 pt-3">
                        <input type="text" name="domain" class="form-control" placeholder="Domain"
                               aria-label="State" required>
                    </label>
                    <label class="col-12 pt-3">
                                            <textarea class="form-control" name="description"
                                                      id="exampleFormControlTextarea1" placeholder="Mô tả"
                                                      rows="3"></textarea>
                    </label>
                    <div class="col-12 text-center mt-3">
                        <button type="button" class="btn mybt6 fw-bolder">Hủy</button>
                        <button type="submit" class="btn ps-3 pe-3 btn-outline-secondary">Tạo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
