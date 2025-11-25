<div class="sidenav navbar justify-content-center border mb-2 fw-bold">
    <a class="navbar-brand text-white" href="{{ url('/') }}">
        PR LOGO
    </a>
</div>

@php
    $activeDropdown = in_array(url()->current(), [
        route('user.list'),
        route('department.list')
    ]);
@endphp

<div class="nav nav-pills d-flex flex-column p-2 gap-2">
    @if(auth()->user()->role_id == 1)
    <a href="{{ route('approval.setup') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('approval.setup') ? 'active-current' : '' }}">
        Approval Setup
    </a>
    <a href="javascript:void(0);" class="fs-5 tab-link nav-link text-white {{ $activeDropdown ? 'active-current' : '' }}" data-bs-toggle="collapse" data-bs-target="#usersDropdown" aria-expanded="{{ $activeDropdown ? "true" : "false" }}">
        User Management
    </a>

    <div class="collapse p-2 rounded {{ $activeDropdown ? 'show' : '' }}" id="usersDropdown">
        <ul class="nav flex-column gap-2">
            <li class="nav-item ps-2 {{ url()->current() == route('user.list') ? 'active-current' : '' }}">
                <a href="{{ route('user.list') }}" class="nav-link text-white">
                    Users
                </a>
            </li>
            <li class="nav-item ps-2 {{ url()->current() == route('department.list') ? 'active-current' : '' }}">
                <a href="{{ route('department.list') }}" class="nav-link text-white">
                    Departments
                </a>
            </li>
        </ul>
    </div>
    @endif

    <a href="{{ route('requisition.form') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.form') ? 'active-current' : '' }}">
        Purchase Requisition
    </a>
    <a href="{{ route('requisition.history') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.history') ? 'active-current' : '' }}">
        PRF History
    </a>
</div>

