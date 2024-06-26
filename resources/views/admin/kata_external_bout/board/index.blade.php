@extends('admin.master')
@section('admin')

<div class="content-wrapper" id="content">
    <div class="container-full">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.external_bout') }}">Kata External Competition</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $external_comp_id}}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ $external_competition->name}}</li>
            </ol>
        </nav>

        <input id="btn_filter_data" type="hidden"
            data-href="{{URL::to('admin/external_bout/board/'.$external_comp_id.'/report_kata')}}" />
        <div id="div_report"></div>

        <div class="modal fade" id="importExcelModal" tabindex="-1"></div>
        <div class="modal fade" id="result_detailsModal" tabindex="-1"></div>
        <div class="modal fade" id="bout_detailsModal" tabindex="-1"></div>

        <div class="modal fade" tabindex="-1" id="form_submit_message">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Message</h5>
                    </div>
                    <div class="modal-body">
                        <p>
                            <span id="form_submit_message_span"></span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection