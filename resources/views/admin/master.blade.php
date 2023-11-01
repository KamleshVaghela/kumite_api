<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="initial-scale=1, shrink-to-fit=no, width=device-width" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- CSS -->
    <!-- Add Material font (Roboto) and Material icon as needed -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i|Roboto+Mono:300,400,700|Roboto+Slab:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" rel="stylesheet" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Two+Tone" rel="stylesheet" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Sharp" rel="stylesheet" crossorigin>

    <link rel="stylesheet" href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet"> --}}

    <!-- Add Material CSS, replace Bootstrap CSS -->
    <link href="{{ asset('static/material2/css/material.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

    <link href="{{ asset('static/gijgo/dist/combined/css/gijgo.min.css') }}" rel="stylesheet">
    {{-- <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('static/bootstrap4-toggle/dist/css/bootstrap4-toggle.min.css') }}" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet"> --}}
    @php
        $route =  Route::current()->getName();
    @endphp
    @if($route == 'admin.board.bout.data_table')
        {{-- <link href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fc-3.3.1/r-2.2.6/rr-1.2.7/sl-1.3.1/datatables.css" rel="stylesheet" crossorigin>
        <link href="https://cdn.jsdelivr.net/gh/djibe/material@4.6.2-1.0/css/material-plugins.min.css" rel="stylesheet" crossorigin> --}}
        <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">
    @endif
    <style>
        #content {
          height: 90vh;
        }
        #report_list {
            max-height: 85vh;
            overflow-x: scroll;
            display: flex;
        }
        #form_edit_activity .select2-search__field {
            width: 27.75em !important;
        }
    </style>
  </head>
  <body>
    @include('admin.body.header')
    @include('admin.body.sidebar')

    <!-- Content Wrapper. Contains page content -->
    @yield('admin')

    <!-- /.content-wrapper -->
    @include('admin.body.footer')


    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <!-- Then Material JavaScript on top of Bootstrap JavaScript -->
    <script src="{{ asset('static/material2/js/material.min.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>

    <script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
    <script src="{{ asset('static/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('static/gijgo/dist/combined/js/gijgo.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('static/bootstrap4-toggle/dist/js/bootstrap4-toggle.min.js') }}"></script>

    {{-- <script src="{{ asset('togglemode.js') }}"></script> --}}
    <script src="{{ asset('ajaxsetup.js') }}"></script>
    <script src="{{ asset('common.js?d='.time()) }}"></script>

    
    @if($route == 'admin.competition')
        <script src="{{ asset('admin/competition.js?d='.time()) }}"></script>
    @endif
    @if($route == 'admin.board.bout')
        <script src="{{ asset('admin/bout.js?d='.time()) }}"></script>
    @endif
    @if($route == 'admin.board')
        <script src="{{ asset('admin/board.js?d='.time()) }}"></script>
    @endif
    @if($route == 'admin.board.bout')
        <script src="{{ asset('admin/board.js?d='.time()) }}"></script>
    @endif
    @if($route == 'admin.default_category')
        <script src="{{ asset('admin/board.js?d='.time()) }}"></script>
    @endif
    @if($route == 'admin.school_master')
        <script src="{{ asset('admin/school_master.js?d='.time()) }}"></script>
    @endif

    @if($route == 'admin.board.bout.data_table')
        {{-- <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.68/build/pdfmake.min.js" integrity="sha256-Xf58sgO5ClVXPyDzPH+NtjN52HMC0YXBJ3rp8sWnyUk=" crossorigin></script>
        <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.68/build/vfs_fonts.js" integrity="sha256-vEmrkqA2KrdjNo0/IWMNelI6jHuWAOkIJxGf88r4iic=" crossorigin></script>
        <script src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fc-3.3.1/r-2.2.6/rr-1.2.7/sl-1.3.1/datatables.min.js" crossorigin></script> --}}
        <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>
        <script src="{{ asset('admin/board.js?d='.time()) }}"></script>
    @endif
</body>
</html>
