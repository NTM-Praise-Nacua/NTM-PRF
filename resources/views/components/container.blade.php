<div class="bg-white rounded-3 shadow-sm main-wrapper">
    @if ($pageTitle ?? false)
    <h2 class="fw-bold d-inline">{{ $pageTitle }}</h2>
    @endif

    {{ $slot }}
</div>