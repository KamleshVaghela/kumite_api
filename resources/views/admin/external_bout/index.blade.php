@extends('admin.master')
@section('admin')
<div class="content-wrapper" id="content">
    <div class="container-full">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">External Bout Generation</li>
            </ol>
        </nav>

        <div id="div_report"></div>
        <div id="div_edit"></div>
        <div class="modal fade" id="addModal" tabindex="-1"></div>

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
<div class="navdrawer navdrawer-right navdrawer-default" id="navdrawerRight" tabindex="-1" style="display: none;"
    aria-hidden="true">
    <div class="navdrawer-content">
        <div class="navdrawer-header">
            <a class="navbar-brand px-0" href="javascript:void(0)">Item Filters</a>
        </div>
        <form class="px-4" id="form_filter">
            @csrf
            <button id="btn_filter_data" type="button" class="btn btn-primary"
                data-href="{{URL::to('admin/external_bout/report')}}">Filter</button>
        </form>
    </div>
</div>
<div class="bd-example">
    <div class="fab-actions">
        <button id="btnFabAdd" aria-expanded="false" data-href="{{URL::to('admin/external_bout/create')}}"
            aria-haspopup="true" class="btn btn-float btn-primary" type="button">
            <i class="material-icons">add</i>
        </button>
    </div>
</div>
@endsection