<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">
                Bout Details
            </h5>
        </div>

        <div id="div_ajax_{{$details_key}}">
        </div>
        <div class="modal-body">
            <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}"
                data-toggle="validate" role="form">
                @csrf
                <div class="form-group form-ripple position-relative">
                    <label for="category_group">Bout Category</label>
                    <select id="category_group" name="category_group" class="form-control select2" style="width: 100%;">
                        @foreach($default_category as $rec)
                        <option value="{{$rec->category_group}}">{{$rec->category_group}}</option>
                        @endforeach
                    </select>
                    <small id="category_group_error" class="form-text post_error"></small>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary"
                data-href="{{URL::to('/admin/competition/board/'.$decrypted_comp_id.'/'.$details_key)}}"
                id="{{$details_key}}Accept">Generate Bouts</button>
        </div>
    </div>
</div>