<div class="sidenav navbar justify-content-center align-items-center mb-2 fw-bold py-0 position-relative" style="height: 48px;">
    <a class="navbar-brand text-white py-0 d-flex align-items-center" href="javascript:void(0);">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="height: 28px; color: #2C76E7;"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
            <path fill="currentColor" d="M128 64C92.7 64 64 92.7 64 128L64 512C64 547.3 92.7 576 128 576L308 576C285.3 544.5 272 505.8 272 464C272 363.4 349.4 280.8 448 272.7L448 234.6C448 217.6 441.3 201.3 429.3 189.3L322.7 82.7C310.7 70.7 294.5 64 277.5 64L128 64zM389.5 240L296 240C282.7 240 272 229.3 272 216L272 122.5L389.5 240zM608 464C608 384.5 543.5 320 464 320C384.5 320 320 384.5 320 464C320 543.5 384.5 608 464 608C543.5 608 608 543.5 608 464zM521.4 403.1C528.5 408.3 530.1 418.3 524.9 425.4L460.9 513.4C458.1 517.2 453.9 519.6 449.2 519.9C444.5 520.2 439.9 518.6 436.6 515.3L396.6 475.3C390.4 469.1 390.4 458.9 396.6 452.7C402.8 446.5 413 446.5 419.2 452.7L446 479.5L499 406.6C504.2 399.5 514.2 397.9 521.4 403.1z"/></svg>
            {{-- <img src="{{ asset('images/PurchaseFlow.png') }}" alt="purchaseflow-logo" style="height: 48px; object-fit: contain;"> --}}
            <span>&nbsp;PurchaseFlow</span>
        </a>
    <x-menu></x-menu>
</div>

@php
    $activeDropdown = in_array(url()->current(), [
        route('user.list'),
        route('department.list'),
        route('position.list'),
    ]);

    $isRequisition = in_array(url()->current(), [
        route('requisition.form'),
        request()->routeIs('requisition.edit'),
    ]);

    $user = auth()->user();
@endphp

<div class="nav nav-pills d-flex flex-column p-2 gap-2">
    <a href="{{ route('home') }}" class="d-flex justify-content-between align-items-center fs-5 tab-link nav-link text-white {{ url()->current() == route('home') ? 'active-current' : '' }}"
        >
        Dashboard
        @if ($sidebarCounters > 0)
            <span class="bg-danger rounded-circle d-flex justify-content-center align-items-center" style="font-size: 12px; width: 20px; height: 20px;">{{ $sidebarCounters }}</span>
        @endif
    </a>
    @if($user->role_id == 1 && $user->id == 1)
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
            <li class="nav-item ps-2 {{ url()->current() == route('position.list') ? 'active-current' : '' }}">
                <a href="{{ route('position.list') }}" class="nav-link text-white">
                    Positions
                </a>
            </li>
        </ul>
    </div>
    @endif

    <a href="{{ route('requisition.history') }}" class="d-flex justify-content-between align-items-center fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.history') || $isRequisition ? 'active-current' : '' }}">
        Purchase Requisition
        @if ($requisitionCounter > 0)
            <span class="bg-danger rounded-circle d-flex justify-content-center align-items-center" style="font-size: 12px; width: 20px; height: 20px;">{{ $requisitionCounter }}</span>
        @endif
    </a>
    {{-- <a href="{{ route('requisition.history') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.history') ? 'active-current' : '' }}">
        PRF History
    </a> --}}
</div>

