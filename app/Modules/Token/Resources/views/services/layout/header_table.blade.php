<thead>
<tr class="mt-3">
    <th  scope="col" class="align-middle">
{{--        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tên Service
        </a>
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Domain
        </a>
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            IP
        </a>
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Endpoint IP
        </a>
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Endpoint Domain
        </a>
    </th>
    <th  scope="col">
        <a class="dropdown-toggle btn"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Status
        </a>
    </th>


@if(!empty($checkLast) && $checkLast != 1)
        <th scope="col">
            <a class="dropdown-toggle btn "  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Last Modified
            </a>
        </th>
@endif

</tr>
</thead>
