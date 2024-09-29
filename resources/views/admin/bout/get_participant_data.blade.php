<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="min-width: 700px">
        <div class="modal-header">
            <h5 class="modal-title" id="edit_viewModalScrollableTitle">Participant</h5>
        </div>
        <div id="div_ajax_edit_view"></div>
        <div class="modal-body">
            <form class="px-4 needs-validation" id="form_add" name="form_add" data-toggle="validate" role="form">
                @csrf

                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="organization">Name</label>
                        {{ $participant->full_name}}
                        <small id="organization_error" class="form-text post_error"></small>
                    </div>
                </div>
                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="organization">Gender</label>
                        {{$participant->gender}}
                        <select id="inputState" class="form-control" id="gender" name="gender">
                            <option value="Male" @if($participant->gender=="Male") selected @endif >Male</option>
                            <option value="Female" @if($participant->gender=="Female") selected @endif >Female</option>
                        </select>
                        <small id="gender_error" class="form-text post_error"></small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary"
                data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/data_table/'.$participant_id.'/post_participant_data')}}"
                id="addModalAccept">Accept</button>
        </div>
    </div>
</div>