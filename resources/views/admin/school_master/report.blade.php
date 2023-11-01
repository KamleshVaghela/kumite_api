<div class="tbodyDiv">
    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Disctrict</th>
            <th scope="col">School Name</th>
          </tr>
        </thead>
        <tbody>
            @forelse($schools as $key=>$rec)
                <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{$rec->DISTRICT}}</td>
                    <td>{{$rec->name}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3"><p class="bg-danger text-white p-1">No Item data found</p></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>