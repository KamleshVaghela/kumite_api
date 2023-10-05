<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
              Update Competition Details
          </h5>
  </div>

  <div id="div_ajax_{{$details_key}}">
  </div>
  <div class="modal-body">
      <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}" data-toggle="validate" role="form">
          @csrf
          <div class="form-group">
              <div class="textfield-box form-ripple position-relative">
                  <label for="karate_ka_display">Karate-Ka Display</label> 
                  <input class="form-control" id="karate_ka_display" name="karate_ka_display" type="text" 
                  value="{{$competition->KARATE_KA_DISPLAY}}"
                  required="required" maxlength="100">
                  <small id="karate_ka_display_error" class="form-text post_error"></small>
              </div>
          </div>
          <div class="form-group">
            <div class="textfield-box form-ripple position-relative">
                <label for="remarks">Remarks</label>
                <input class="form-control" id="remarks" name="remarks" type="text" 
                value="{{$competition->REMARKS}}"
                required="required" maxlength="100">
                <small id="remarks_error" class="form-text post_error"></small>
            </div>
        </div>
      </form>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
      <button type="button" class="btn btn-primary" data-href="{{URL::to('/admin/competition/board/'.$decrypted_comp_id.'/'.$details_key)}}" id="{{$details_key}}Accept">Accept</button>
    </div>
</div>
</div>

