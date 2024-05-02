@extends('admin.master')
@section('admin')
<style>
.table {
    border-collapse: collapse;
}

.table,
.table th,
.table td {
    border: 1px solid #ccc;
}

.table th,
.table td {
    padding: 0.5rem;
}

.table th {
    position: relative;
}

.resizer {
    position: absolute;
    top: 0;
    right: 0;
    width: 5px;
    cursor: col-resize;
    user-select: none;
}

.resizer:hover,
.resizing {
    border-right: 2px solid blue;
}

.resizable {
    border: 1px solid gray;
    height: 100px;
    width: 100px;
    position: relative;
}

.tbodyDiv {
    /* max-height: clamp(5em,10vh,250px); */
    max-height: clamp(70em, 10vh, 250px);
    overflow: auto;
}
</style>
<div class="content-wrapper" id="content">
    <div class="container-full">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Competitions</li>
                <li class="breadcrumb-item active" aria-current="page">{{ $decrypted_comp_id}}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ $competition->COMP_NAME}}</li>
                <li class="breadcrumb-item active" aria-current="page">Kata Bouts Board</li>
            </ol>
        </nav>
        {{-- <div class="modal fade" id="addModal" tabindex="-1"></div>
        <div class="modal fade" id="editModal" tabindex="-1"></div>
        <div class="modal fade" id="addInwardModal" tabindex="-1"></div>
        <div class="modal fade" id="addOutwardModal" tabindex="-1"></div> --}}

        <input id="btn_filter_data" type="hidden"
            data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/kata/bout/report')}}" />
        <div id="div_report"></div>
        <div id="div_edit"></div>

        <div class="modal fade" tabindex="-1" id="form_submit_message">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Message</h5>
                    </div>
                    <div class="modal-body">
                        <p><span id="form_submit_message_span"></span></p>
                    </div>
                    <div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal"
                            type="button">Ok</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection