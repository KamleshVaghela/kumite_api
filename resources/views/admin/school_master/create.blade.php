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
                <div class="form-group textfield-box form-ripple">
                    <label>District</label>
                    <select id="geo_id" name="geo_id" class="form-control select2" style="width: 100%;">
                        @foreach($districts as $key=>$rec)
                            <option value="{{$rec->GEOID}}">{{$rec->DISTRICT}}</option>
                        @endforeach
                    </select>
                    <small id="geo_id_error" class="form-text post_error"></small>
                </div>
                <div class="form-group">
                    <div class="textfield-box form-ripple position-relative">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text" required="required" maxlength="250">
                        <small id="name_error" class="form-text post_error"></small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary" data-href="{{URL::to('admin/school_master/store')}}" id="addModalAccept">Accept</button>
          </div>
    </div>
</div>

