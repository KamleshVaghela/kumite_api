@php
$prefix = Request::route()->getPrefix();
$route =  Route::current()->getName();

@endphp
<div class="navdrawer navdrawer-default" id="navdrawerDefault" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="navdrawer-content">
      <div class="navdrawer-header">
        <a class="navbar-brand px-0" href="javascript:void(0)">Pratibha Academy</a>
      </div>
      <p class="navdrawer-subheader">Link</p>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Home</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.competition' ? 'active' : '' }}" href="{{ route('admin.competition') }}">Competition</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.default_category' ? 'active' : '' }}" href="{{ route('admin.default_category') }}">Category</a>
      </nav>
      {{-- <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.staff' ? 'active' : '' }}" href="{{ route('admin.staff') }}">Staff Master</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.leaves' ? 'active' : '' }}" href="{{ route('admin.leaves') }}">Staff Leaves</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.branch_order' ? 'active' : '' }}" href="{{ route('admin.branch_order') }}">Branch Orders</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.expense_categories' ? 'active' : '' }}" href="{{ route('admin.expense_categories') }}">Expense Category</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'admin.income_categories' ? 'active' : '' }}" href="{{ route('admin.income_categories') }}">Income Category</a>
      </nav>
      <div class="navdrawer-divider"></div>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link {{ $route == 'staff.dashboard' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">Back to Staff</a>
      </nav>
      <nav class="navdrawer-nav">
        <a class="nav-item nav-link" href="{{ route('frontdesk.logout') }}">Logout</a>
      </nav> --}}
      {{-- <div class="custom-control custom-switch mx-5">
        <input type="checkbox" class="custom-control-input" id="darkSwitch">
        <label class="custom-control-label" for="darkSwitch">Toggle Dark mode</label>
        </div> --}}
    </div>

</div>
