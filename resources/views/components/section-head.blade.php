@push('css')
    <style>
        .section-head {
            background: #193cb8;
            text-indent: 12px;
        }
    </style>
@endpush
<div class="section-head text-white py-1 fst-italic">
    {{ $headTitle }}
    @if ($headerButton ?? false)
    <button type="button" class="upload-pdf btn btn-sm btn-secondary mx-3">{{ $buttonLabel ?? 'nolabel' }}</button>
    <input type="file" class="d-none" id="pdf_file" name="pdf_file">
    @endif
</div>
@push('js')
    <script>
        $('.upload-pdf').off('click').on('click', function() {
            $('#pdf_file').click();
        })
    </script>
@endpush