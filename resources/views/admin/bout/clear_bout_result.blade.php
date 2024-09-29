<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="min-width: 700px">
        <div class="modal-header">
            <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Clear Result</h5>
        </div>
        <div id="div_ajax_{{$details_key}}"></div>
        <div class="modal-body">
            <form class="px-4 needs-validation" id="form_add" name="form_add" data-toggle="validate" role="form">
                @csrf
                <div class="alert alert-danger" role="alert">
                    Do you want to clear result for "{{$boutObj->bout_number}}: {{$boutObj->category}}"??
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary"
                data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/post_clear_bout_result')}}"
                id="addModalAccept">Accept</button>
        </div>
    </div>
</div>