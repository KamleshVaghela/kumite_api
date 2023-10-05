@if (Session::has('error'))
  <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      {{ session::get('error') }}
  </div>
@endif