<div class="modal-body">
    <form class="form row" id="form-name" action="{{ route('services.store') }}"
          method="post" data-url-login-by-password="#">
        @csrf
        <label class="col-12 pt-3 position-relative my-hover">
            <label class="position-absolute a2">Tên Service</label>
            <input name="name" type="text" id="t1" class="form-control myinput2" required>
        </label>

        <label class="col-12 pt-3 position-relative my-hover">
            <label class="position-absolute a2">App ID</label>
            <input name="app_id" type="text" id="t1" class="form-control myinput2" required>
        </label>

        <label class="col-12 pt-3 position-relative my-hover">
            <label class="position-absolute a2">Server IP</label>
            <input name="server_ip" type="text" id="t1" class="form-control myinput2" required>
        </label>

        <label class="col-12 pt-3 position-relative my-hover">
            <label class="position-absolute a2">Domain</label>
            <input name="domain" type="text" id="t1" class="form-control myinput2" required>
        </label>

        <label class="col-12 pt-3">
            <select name="endpoint_server_ip" class="form-select"
                    aria-label="Default select example">
                <option selected disabled>Endpoint Server IP</option>
                @if(!empty($endpoints))
                    @foreach($endpoints['data'] as $key => $item)
                        <option value="{{ $item['server_ip'] }}">{{ $item['server_ip'] }}</option>
                    @endforeach
                @endif
            </select>
        </label>
        <label class="col-12 pt-3">
            <select name="endpoint_domain" class="form-select"
                    aria-label="Default select example">
                <option selected disabled>Endpoint Domain</option>
                @if(!empty($endpoints))
                    @foreach($endpoints['data'] as $key => $item)
                        <option value="{{ $item['domain'] }}">{{ $item['domain'] }}</option>
                    @endforeach
                @endif
            </select>
        </label>
        <div class="col-12 text-center mt-3">
            <button type="button" class="btn ps-3 pe-3 btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
            <button id="SBCreateService" type="submit" class="btn ps-3 pe-3 btnvr3">Tạo</button>
        </div>
    </form>
</div>
<script>
    function clearFormData() {
        const form = document.getElementById("SBCreateService");
        form.reset();
    }
</script>
