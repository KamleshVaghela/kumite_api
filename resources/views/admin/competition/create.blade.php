<div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">
                    New Competition
                </h5>
        </div>

        <div id="div_ajax_add">
        </div>
        <div class="modal-body">
            <form class="px-4 needs-validation" id="form_add" name="form_add" data-toggle="validate" role="form">
                @csrf
                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text" required="required" maxlength="100">
                        <small id="name_error" class="form-text post_error"></small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary" data-href="{{URL::to('admin/competition/store')}}" id="addModalAccept">Accept</button>
          </div>
    </div>
</div>

