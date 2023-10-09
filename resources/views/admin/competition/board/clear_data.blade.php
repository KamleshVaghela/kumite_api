<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
              Clear Bout Data
          </h5>
  </div>

  <div id="div_ajax_{{$details_key}}">
  </div>
  <div class="modal-body">
      <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}" data-toggle="validate" role="form">
          @csrf            
          Are you sure you want to Clear Bout Data?
      </form>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
      <button type="button" class="btn btn-primary" data-href="{{URL::to('/admin/competition/board/'.$decrypted_comp_id.'/'.$details_key)}}" id="{{$details_key}}Accept">Submit</button>
    </div>
</div>
</div>

