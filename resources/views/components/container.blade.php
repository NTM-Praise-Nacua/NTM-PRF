<div class="bg-white rounded-3 shadow-sm" style="padding: 32px !important; width: 1200px; max-width: 1200px; margin-top: 32px;">
    @if ($pageTitle ?? false)
    <h2 class="fw-bold d-inline">{{ $pageTitle }}</h2>
    @endif

    {{ $slot }}
</div>