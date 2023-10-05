<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
              Update Important Dates
          </h5>
  </div>

  <div id="div_ajax_{{$details_key}}">
  </div>
  <div class="modal-body">
      <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}" data-toggle="validate" role="form">
          @csrf
          <div class="form-group">
            <div class="textfield-box form-ripple position-relative">
                <label for="comp_karate_ka_close_date">Karate-Ka End Date</label> 
                <input class="form-control" id="comp_karate_ka_close_date" name="comp_karate_ka_close_date" type="date" 
                value="{{$competition->CLOSE_DATE_K}}"
                required="required">
                <small id="comp_karate_ka_close_date_error" class="form-text post_error"></small>
            </div>
          </div>
          <div class="form-group">
            <div class="textfield-box form-ripple position-relative">
                <label for="comp_coach_close_date">Coach End Date</label> 
                <input class="form-control" id="comp_coach_close_date" name="comp_coach_close_date" type="date" 
                value="{{$competition->CLOSE_DATE_C}}"
                required="required">
                <small id="comp_coach_close_date_error" class="form-text post_error"></small>
            </div>
          </div>
          <div class="form-group">
              <div class="textfield-box form-ripple position-relative">
                  <label for="comp_start_date">Start Date</label> 
                  <input class="form-control" id="comp_start_date" name="comp_start_date" type="date" 
                  value="{{$competition->COMP_DATE}}"
                  required="required">
                  <small id="comp_start_date_error" class="form-text post_error"></small>
              </div>
          </div>
          <div class="form-group">
            <div class="textfield-box form-ripple position-relative">
                <label for="comp_end_date">Start Date</label> 
                <input class="form-control" id="comp_end_date" name="comp_end_date" type="date" 
                value="{{$competition->COMP_END_DATE}}"
                required="required">
                <small id="comp_end_date_error" class="form-text post_error"></small>
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

