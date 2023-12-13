<div class="modal-body">
    <form class="form row" id="form-name" action="{{route('createPaddingService')}}"
          method="post" data-url-login-by-password="#">
        @csrf
        <label class="col-12 pt-3 position-relative my-hover">
            <label class="position-absolute a2">Tên service</label>
            <input name="name" type="text" id="t1" class="form-control myinput2"
                   aria-describedby="text1" required>
        </label>

        <label class="col-12 pt-3 my-hover">
            <input name="appId" type="text" class="form-control"
                   placeholder="APP ID" aria-label="State" required>
        </label>
        <label class="col-12 pt-3 my-hover">
            <input name="serveIp" type="text" class="form-control"
                   placeholder="Service IP" aria-label="State" required>
        </label>
        <label class="col-12 pt-3">
            <input name="domain" type="text" class="form-control" placeholder="Domain"
                   aria-label="State" required>
        </label>
        <label class="col-12 pt-3">
            <select name="serveIpTransfer" class="form-select"
                    aria-label="Default select example">
                <option selected>Service IP transfer</option>
                @if(!empty($endpoint))
                    @foreach($endpoint['list'] as $key=>$itemend)

                        <option
                            value="{{$itemend['serveIp']}}">{{$itemend['serveIp']}}</option>
                    @endforeach
                @endif
            </select>
        </label>
        <label class="col-12 pt-3">
            <select name="domainTransfer" class="form-select"
                    aria-label="Default select example">
                <option selected>Domain transfer</option>
                @if(!empty($endpoint))
                    @foreach($endpoint['list'] as $key=>$itemend)

                        <option
                            value="{{$itemend['domain']}}">{{$itemend['domain']}}</option>
                    @endforeach
                @endif
            </select>
        </label>
        <div class="col-12 text-center mt-3">
            <button type="button" class="btn ps-3 pe-3 btn-outline-secondary">Hủy</button>
            <button id="SBCreateService" type="submit" class="btn ps-3 pe-3  btnvr3">Tạo</button>
        </div>

    </form>
</div>
<script>
    function clearFormData() {
        var form = document.getElementById("SBCreateService");
        form.reset();
    }
</script>
