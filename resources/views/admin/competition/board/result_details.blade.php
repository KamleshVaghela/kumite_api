<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
              Result Details
          </h5>
  </div>

  <div id="div_ajax_{{$details_key}}">
  </div>
  <div class="modal-body">
  <table class="table table-striped">
    <tbody>
        <tr class="table-primary">
            <th scope="row">Coach</th>
            <th scope="row">Gold</th>
            <th scope="row">Silver</th>
            <th scope="row">Bronze</th>
        </tr>
    @forelse($result_data as $key=>$rec)
        <tr>
            <td scope="row">{{$rec->external_coach_name}}</td>
            <td scope="row">{{$rec->total_gold}}</td>
            <td scope="row">{{$rec->total_silver}}</td>
            <td scope="row">{{$rec->total_bronze_1 + $rec->total_bronze_2}}</td>
        </tr>
        @empty
            <p class="bg-danger text-white p-1">No Item data found</p>
        @endforelse
    </tbody>
    </table>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
    </div>
</div>
</div>

