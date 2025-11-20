@push('css')
    <style>
        .tab-link {
            color: white;
        }
        .tab-link:hover {
            color: lightgray;
        }
    </style>
@endpush

<div class="navbar justify-content-center border mb-2 fw-bold">
    <a class="navbar-brand text-white" href="{{ url('/') }}">
        PR LOGO
    </a>
</div>

<div class="nav nav-pills d-flex flex-column p-2">
    <a href="{{ route('approval.setup') }}" class="fs-5 tab-link nav-link text-white mb-3">
        Approval Setup
    </a>
    <a href="#" class="fs-5 tab-link nav-link text-white">
        Users
    </a>
</div>