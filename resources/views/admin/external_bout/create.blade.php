<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalScrollableTitle">
                New School
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
                        <input class="form-control" id="name" name="name" type="text" required="required"
                            maxlength="250">
                        <small id="name_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="short_description">Short Description</label>
                        <input class="form-control" id="short_description" name="short_description" type="text"
                            required="required" maxlength="250">
                        <small id="short_description_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="additional_details">Additional Details</label>
                        <input class="form-control" id="additional_details" name="additional_details" type="text"
                            required="required" maxlength="250">
                        <small id="additional_details_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple">
                        <label for="start_date">Start Date</label>
                        <input class="form-control" id="start_date" name="start_date" type="date"
                            value="{{ date('Y-m-01') }}">
                        <small id="start_date_error" class="form-text post_error"></small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="textfield-box form-ripple">
                        <label for="end_date">End Date</label>
                        <input class="form-control" id="end_date" name="end_date" type="date"
                            value="{{ date('Y-m-01') }}">
                        <small id="end_date_error" class="form-text post_error"></small>
                    </div>
                </div>


            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary" data-href="{{URL::to('admin/external_bout/store')}}"
                id="addModalAccept">Accept</button>
        </div>
    </div>
</div>