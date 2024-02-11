@php
$prefix = Request::route()->getPrefix();
$route = Route::current()->getName();

@endphp
<div class="navdrawer navdrawer-default" id="navdrawerDefault" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="navdrawer-content">
        <div class="navdrawer-header">
            <a class="navbar-brand px-0" href="javascript:void(0)">Pratibha Academy</a>
        </div>
        <p class="navdrawer-subheader">Link</p>
        <nav class="navdrawer-nav">
            <a class="nav-item nav-link {{ $route == 'admin.dashboard' ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">Home</a>
        </nav>
        <nav class="navdrawer-nav">
            <a class="nav-item nav-link {{ $route == 'admin.competition' ? 'active' : '' }}"
                href="{{ route('admin.competition') }}">Competition</a>
        </nav>
        <nav class="navdrawer-nav">
            <a class="nav-item nav-link {{ $route == 'admin.default_category' ? 'active' : '' }}"
                href="{{ route('admin.default_category') }}">Category</a>
        </nav>
        <nav class="navdrawer-nav">
            <a class="nav-item nav-link {{ $route == 'admin.school_master' ? 'active' : '' }}"
                href="{{ route('admin.school_master') }}">School Master</a>
        </nav>
        <nav class="navdrawer-nav">
            <a class="nav-item nav-link {{ $route == 'admin.external_bout' ? 'active' : '' }}"
                href="{{ route('admin.external_bout') }}">External Bouts</a>
        </nav>
    </div>

</div>