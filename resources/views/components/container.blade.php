<div class="bg-white rounded p-5 shadow-sm">
    @if ($pageTitle ?? false)
    <h2 class="fw-bold d-inline">{{ $pageTitle }}</h2>
    @endif

    {{ $slot }}
</div>