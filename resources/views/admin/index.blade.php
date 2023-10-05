@extends('admin.master')
@section('admin')

<div class="content-wrapper" id="content">

	  <div class="container-full">
        @if (Session::has('error'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session::get('error') }}
        </div>
    @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Admin Desk Dashboard</a></li>
            </ol>
        </nav>
        <div>
            <h4> Login Admin Name: {{ Auth::user()->name }}</h4>
        </div>

		<!-- Main content -->
		<!-- /.content -->
	  </div>
  </div>

  @endsection
