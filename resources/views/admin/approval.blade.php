@extends('layouts.app')

@push('css')
    <style>
        .inner-shadow {
            box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.25);
        }
        .tag-main {
            width: 350px;
        }
        .tagLabel {
            display: inline-flex;
            width: 300px;
            user-select: none;
            background: #1447e6;
            clip-path: polygon(0 0, 
            calc(100% - 50px) 0,
            100% 45%,
            100% 55%,
            calc(100% - 50px) 100%,
            0 100%);
            transition: transform .15s ease-in-out;
            pointer-events: none;
        }
        .tagLabel * {
            pointer-events: auto
        }
        .tagLabel:hover {
            transform: scale(1.03);
        }
        .tagLabel:active {
            cursor: grabbing;
        }
        select[name="department"] option {
            color: black;
        }
        select[name="department"]:focus {
            box-shadow: none;
        }
        .popClose {
            width: 35px;
            height: 35px;
            transform: translate(0%, -50%);
            color: #82181a;
            opacity: 0;
            transition: all 0.15s ease-in-out;
            user-select: none;
            cursor: pointer;
            pointer-events: auto !important;
            z-index: 2;
        }
        .popClose:hover {
            opacity: 1;
        }
        .popClose:active {
            opacity: .75;
        }
    </style>
@endpush

@php
    $departments = [
        'IT',
        'SCD',
        'CMD',
        'Accounting',
        'Compliance',
        'Audit',
        'Procurement',
        'CRA',
        'Sales',
        'HRD',
        'Marketing',
        'Execom'
    ];
@endphp

@section('content')
    <x-container pageTitle="Approval Setup">
        <x-section-head headTitle="Approval Process"></x-section-head>

        <div class="row p-3 ">
            <div class="col-3">
                <div class="card pt-3 px-2 pb-1 mb-3">
                    <h5 class="card-title">Request Type</h5>
                    <div class="card-body px-0">
                        <select name="test" id="test" class="form-select">
                            <option selected hidden>Choose Type</option>
                            @forelse ($requestTypes as $type)
                                <option value="{{ $type->slug }}">{{ $type->name }}</option>
                            @empty
                                <option value="">No Request Types Found</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" id="addOrdering">
                    + Add
                </button>

                {{-- <form action="{{ route('add.request.type') }}" method="post">
                    @csrf
                    <input type="text" name="name" placeholder="Name" class="form-control">
                    <input type="text" name="slug" placeholder="Slug" class="form-control">
                    <button class="btn btn-outline-primary" type="submit">submit</button>
                </form> --}}
            </div>
            <div class="col">
                <div class="flex-area d-flex flex-column flex-wrap border rounded overflow-x-auto p-4 inner-shadow" style="height: 350px; row-gap: 16px; column-gap: 16px; background:#f5f5f4;">
                </div>
            </div>
        </div>

        <x-section-head headTitle="Upload PDF Template" :headerButton="true" buttonLabel="Upload"></x-section-head>
    </x-container>
@endsection

@push('js')
    <script>
        $('#addOrdering').on('click', function() {
            const mainWrapper = $('<div></div>');
            mainWrapper.addClass('tag-main position-relative');
            const divWrapper = $('<div></div>');
            divWrapper.addClass('tagLabel align-items-center relative border fs-4 fw-bold text-white p-3 pe-5 shadow-lg position-relative');
            const close = $('<div></div>');
            close.html('&#10006;');
            close.addClass('popClose position-absolute end-0 top-50 rounded bg-danger text-danger-emphasis shadow fs-4 fw-bolder d-flex justify-content-center align-items-center');
            close.click((e) => {
                remove(e.target);
            });

            const circleWrapper = $('<div></div>');
            circleWrapper.addClass('col-3 circle rounded-circle bg-white align-middle text-black');

            const circle = $('<span></span>');
            const dropdownWrapper = $('<div class="col ps-3"></div>');

            const dropDown = $('<select></select>')
                .attr('name', 'department')
                .addClass('form-select fs-4 fw-bold bg-transparent border-0 text-white')
                .css('background-image','none');
                
            const emptyOpt = $('<option></option>');
            emptyOpt.text('Department');
            emptyOpt.attr('hidden', true);
            emptyOpt.attr('selected', true);
            dropDown.append(emptyOpt);

            const departments = @json($departments);
                
            departments.forEach(el => {
                const option = $('<option></option>');
                option.val(el);
                option.text(el);
                dropDown.append(option);
            });

            const tagLabels = $('.tagLabel');
            circle.text(tagLabels.length + 1);

            dropdownWrapper.append(dropDown);
            circleWrapper.append(circle);
            divWrapper.append(circleWrapper, dropdownWrapper);
            mainWrapper.append(divWrapper, close)

            $('.flex-area').append(mainWrapper);
        });

        function remove(el) {
            const parent = $(el).parent();
            parent.remove();
            recalcNumbers();
        }

        function recalcNumbers() {
            $('.tag-main .circle').each(function(index) {
                $(this).text(index + 1);
            });
        }
    </script>
@endpush