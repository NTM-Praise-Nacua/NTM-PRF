@push('css')
    
@endpush

<div class="navbar justify-content-center border mb-2 fw-bold">
    <a class="navbar-brand text-white" href="{{ url('/') }}">
        PR LOGO
    </a>
</div>

<div class="nav nav-pills d-flex flex-column p-2 gap-2">
    <a href="{{ route('approval.setup') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('approval.setup') ? 'active-current' : '' }}">
        Approval Setup
    </a>
    <a href="{{ route('user.list') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('user.list') ? 'active-current' : '' }}">
        Users
    </a>
    <a href="{{ route('requisition.form') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.form') ? 'active-current' : '' }}">
        Purchase Requisition
    </a>
    <a href="{{ route('requisition.history') }}" class="fs-5 tab-link nav-link text-white {{ url()->current() == route('requisition.history') ? 'active-current' : '' }}">
        PRF History
    </a>
</div>