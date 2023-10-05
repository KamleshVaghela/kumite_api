<div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">
                    Work Experience Edit Form
                </h5>
        </div>
        <div id="div_ajax_add">
        </div>
        <div class="modal-body">
            <form class="px-4 needs-validation" id="form_add_inward" name="form_add_inward" data-toggle="validate" role="form">
                @csrf
                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="organization">Organization</label>
                        <input class="form-control" id="organization" name="organization" type="text" required="required" maxlength="100" value="{{$dataObj->organization}}">
                        <small id="organization_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="role">Role</label>
                        <input class="form-control" id="role" name="role" type="text" required="required" maxlength="100" value="{{$dataObj->role}}">
                        <small id="role_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="duration_months">Duration (months)</label>
                        <input class="form-control" id="duration_months" name="duration_months" type="number" value="{{$dataObj->duration_months}}">
                        <small id="duration_months_error" class="form-text post_error"></small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary" data-href="{{URL::to('admin/competition/update/'.$dataObj->id)}}" id="addActivityModalAccept">Accept</button>
          </div>
    </div>
</div>

