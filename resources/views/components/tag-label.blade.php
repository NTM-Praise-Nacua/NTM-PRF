@push('css')
    <style>
        .tagLabel {
            display: inline-flex;
            width: 300px;
            background: #1447e6;
            clip-path: polygon(0 0, calc(100% - 40px) 0, 100% 50%, calc(100% - 40px) 100%, 0 100%);
        }
        .circle {
            height: 50px;
            width: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

<div class="align-items-center relative border tagLabel me-5 fs-3">
    <div class="col-3 circle rounded-circle bg-white align-middle">
        <span>
            {{ $orderNumber }}
        </span>
    </div>
    {{ $requestType }}
</div>