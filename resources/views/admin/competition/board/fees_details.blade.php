<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
              Update Fees Details
          </h5>
  </div>

  <div id="div_ajax_{{$details_key}}">
  </div>
  <div class="modal-body">
      <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}" data-toggle="validate" role="form">
          @csrf
          <div class="form-group">
              <div class="textfield-box form-ripple position-relative">
                  <label for="fees">General</label> 
                  <input class="form-control" id="fees" name="fees" type="number" 
                  value="{{$competition->FEES}}"
                  required="required" maxlength="100">
                  <small id="fees_error" class="form-text post_error"></small>
              </div>
          </div>

          <div class="form-group">
            <div class="textfield-box form-ripple position-relative">
                <label for="fees_kata">Kata</label> 
                <input class="form-control" id="fees_kata" name="fees_kata" type="number" 
                value="{{$competition->FEES_KATA}}"
                required="required" maxlength="100">
                <small id="fees_kata_error" class="form-text post_error"></small>
            </div>
        </div>

        <div class="form-group">
          <div class="textfield-box form-ripple position-relative">
              <label for="fees_kumite">Kumite</label> 
              <input class="form-control" id="fees_kumite" name="fees_kumite" type="number" 
              value="{{$competition->FEES_KUMITE}}"
              required="required" maxlength="100">
              <small id="fees_kumite_error" class="form-text post_error"></small>
          </div>
        </div>

        <div class="form-group">
          <div class="textfield-box form-ripple position-relative">
              <label for="fees_team_kata">Team Kata</label> 
              <input class="form-control" id="fees_team_kata" name="fees_team_kata" type="number" 
              value="{{$competition->FEES_T_KATA}}"
              required="required" maxlength="100">
              <small id="fees_team_kata_error" class="form-text post_error"></small>
          </div>
        </div>

        <div class="form-group">
          <div class="textfield-box form-ripple position-relative">
              <label for="fees_team_kumite">Team Kumite</label> 
              <input class="form-control" id="fees_team_kumite" name="fees_team_kumite" type="number" 
              value="{{$competition->FEES_T_KUMITE}}"
              required="required" maxlength="100">
              <small id="fees_team_kumite_error" class="form-text post_error"></small>
          </div>
        </div>

        <div class="form-group">
          <div class="textfield-box form-ripple position-relative">
              <label for="fees_coach">Coach</label> 
              <input class="form-control" id="fees_coach" name="fees_coach" type="number" 
              value="{{$competition->COACH_FEES}}"
              required="required" maxlength="100">
              <small id="fees_coach_error" class="form-text post_error"></small>
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

