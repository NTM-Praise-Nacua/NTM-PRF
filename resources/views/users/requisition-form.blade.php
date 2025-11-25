@extends('layouts.app')

@push('css')
    <style>
        .connector {
            height: 5px;
            width: 140px;
            z-index: 1;
            /* width: 100%; */
        }
        .form-field__input {
            appearance: none;
            background: transparent;
            border: 0;
            border-bottom: 1px solid #999;
            color: #333;
            display: block;
            font-size: 16px;
            margin-top: 24px;
            outline: 0;
            padding: 0 10px 10px 5px;
            width: 100%;
        }
        .form-field__label {
            font-size: 14px;
            transform: translateY(-14px);
            top: -5px;
            color: #b11adc;
            display: block;
            font-weight: normal;
            left: 0;
            margin: 0;
            padding: 24px 12px 0 5px;
            position: absolute;
            transition: all 0.4s;
            width: 100%;
            pointer-events: none;
        }
        .form-group {
            border-radius: 8px 8px 0 0;
            overflow: hidden;
            position: relative;
            width: 100%;
        }
        .form-check-input {
            height: 20px;
            width: 20px;
        }
        .request-lists {
            height: 150px;
        }
        .form-floating textarea {
            resize: none;
        }
    </style>
@endpush

@php
    $counter = 0;    
@endphp

@section('content')
    <x-container>
        <div class="d-flex justify-content-center">
            <div class="row w-50 h-75">
                <div class="col d-flex flex-column gap-1">
                    <div class="d-flex justify-content-center">
                        <div class="circle fs-3 rounded-circle bg-success text-white">&#10004;</div>
                    </div>
                    <div class="text-center">
                        <h5 class="m-0 fw-bold">Marketing</h5>
                        <p class="m-0">Mon, June 24</p>
                    </div>
                </div>
                <div class="col-1 px-0 d-flex flex-column">
                    <div class="circle position-relative w-100">
                        <div class="connector position-absolute top-50 bg-success"></div>
                    </div>
                    <div class="col"></div>
                </div>
                <div class="col d-flex flex-column gap-1">
                    <div class="d-flex justify-content-center">
                        <div class="circle fs-3 rounded-circle bg-success text-white">&#10004;</div>
                    </div>
                    <div class="text-center">
                        <h5 class="m-0 fw-bold">Sales</h5>
                        <p class="m-0">Tues, June 25</p>
                    </div>
                </div>
                <div class="col-1 px-0 d-flex flex-column">
                    <div class="circle position-relative w-100">
                        <div class="connector position-absolute top-50 bg-success"></div>
                    </div>
                    <div class="col"></div>
                </div>
                <div class="col d-flex flex-column gap-1">
                    <div class="d-flex justify-content-center">
                        <div class="circle fs-3 rounded-circle bg-success text-white">&#10004;</div>
                    </div>
                    <div class="text-center">
                        <h5 class="m-0 fw-bold">Marketing</h5>
                        <p class="m-0">Tues, June 25</p>
                    </div>
                </div>
                <div class="col-1 px-0 d-flex flex-column">
                    <div class="circle position-relative w-100">
                        <div class="connector position-absolute top-50 bg-success"></div>
                    </div>
                    <div class="col"></div>
                </div>
                <div class="col d-flex flex-column gap-1">
                    <div class="d-flex justify-content-center">
                        <div class="circle fs-3 rounded-circle bg-success text-white">&#10004;</div>
                    </div>
                    <div class="text-center">
                        <h5 class="m-0 fw-bold">Procurement</h5>
                        <p class="m-0">Fri, June 28</p>
                    </div>
                </div>
            </div>
        </div>
        <hr class="border border-2 border-black">
        <form action="{{ route('requisition.form.add') }}" class="my-5">
            @csrf
            <h2 class="text-center">PURCHASE REQUISITION FORM</h2>
            <div class="row mb-3">
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="date_request" class="form-field__label">Date & Time requested</label>
                        <input type="text" name="date_request" id="date_request" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="date" name="date_request" id="date_request" class="form-control bg-white" value="{{ now()->toDateString() }}" placeholder="Date & Time requested">
                        <label for="date_request">Date & Time requested</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="date_needed" class="form-field__label">Date Needed</label>
                        <input type="text" name="date_needed" id="date_needed" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="date" name="date_needed" id="date_needed" class="form-control bg-white" value="{{ now()->toDateString() }}" placeholder="Date Needed">
                        <label for="date_needed">Date Needed</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select id="formStat" disabled="disabled" aria-required="true" aria-invalid="false" class="form-select">
                            <option value="0">Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Rejected</option>
                            <option value="3">Executed</option>
                            <option value="4">Confirmed</option>
                        </select>
                        <label for="formStat">Status</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="full_name" id="full_name" class="form-control bg-white" placeholder="Full Name" value="{{ auth()->user()->name }}">
                        <label for="full_name">Full Name</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="contact" id="contact" class="form-control bg-white" placeholder="Contact Num" value="{{ auth()->user()->contact_no }}">
                        <label for="contact">Contact Num</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="position" id="position" class="form-control bg-white" placeholder="Position" value="{{ auth()->user()->position_id }}">
                        <label for="position">Position</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="department" id="department" class="form-control bg-white" placeholder="Department" value="{{ auth()->user()->department_id }}">
                        <label for="department">Department</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="branch" id="branch" class="form-control bg-white" placeholder="Branch" value="Head Office">
                        <label for="branch">Branch</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select name="urgency" id="urgency" class="form-select bg-white">
                            <option value="" selected hidden>Select Level</option>
                            <option value="Highest">Highest (1-2 Hours)</option>
                            <option value="High">High (1-2 Days)</option>
                            <option value="Medium">Medium (3-6 Days)</option>
                            <option value="Low">Low (1-2 Weeks)</option>
                            <option value="Lowest">Lowest (3-4 Weeks)</option>
                        </select>
                        <label for="urgency">Urgency</label>
                    </div>
                </div>
            </div>

            <x-section-head headTitle="Type of Request"></x-section-head>

            <div class="request-lists container my-3 d-flex flex-column flex-wrap gap-2 overflow-x-auto">
                @forelse ($requestTypes as $item)
                    <div class="checkbox-group">
                        <input type="checkbox" name="request_type" id="request_type{{ $counter }}" class="form-check-input rounded-0 single-check" value="{{ $item->id }}">
                        <label for="request_type{{ $counter }}" class="fs-5 ms-2">{{ $item->name }}</label>
                    </div>
                    @php
                        $counter++;
                    @endphp
                @empty
                    <p class="text-center">Error Fetching Request Types</p>
                @endforelse
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Request Details" id="request_details" style="height: 130px"></textarea>
                <label for="request_details">Request Details</label>
            </div>

            <div class="upload-pdf-group row mb-3">
                <div class="col">
                    <label for="upload_pdf" class="fs-5 fw-bold">Upload PDF</label>
                    <input type="file" name="upload_pdf" id="upload_pdf" class="form-control w-75">
                </div>
                <div class="col-8 d-flex gap-1">
                    <div class="col">
                        <div class="col d-flex align-items-center">
                            <p class="m-0 fs-5 fw-bold">Accountable Department</p>
                        </div>
                        <select name="next_department" id="next_department" class="form-select form-select-sm w-75 bg-white">
                            <option value="" selected hidden>Select Department</option>
                            <option value="Marketing Department">Marketing Department</option>
                            <option value="Accounting Department">Accounting Department</option>
                            <option value="IT Department">IT Department</option>
                            <option value="Sales Department">Sales Department</option>
                        </select>
                    </div>
                    <div class="col">
                        <div class="col d-flex align-items-center">
                            <p class="m-0 fs-5 fw-bold">Select Employee</p>
                        </div>
                        <select name="assign_employee" id="assign_employee" class="form-select form-select-sm w-75 bg-white">
                            <option value="" selected hidden>Select Employee</option>
                            <option value="test">test</option>
                            <option value="test">test</option>
                            <option value="test">test</option>
                            <option value="test">test</option>
                        </select>
                    </div>
                </div>
            </div>

            <x-section-head headTitle="FOR APPROVER"></x-section-head>

            <h6 class="my-3">LIST OF APPROVERS</h6>
            <div class="d-flex flex-wrap">
                <div class="rounded-2 bg-info-subtle text-primary px-2 py-1">Test Test</div>
            </div>

            <hr>

            <div class="button-group float-end">
                <button type="button" class="btn btn-sm btn-primary">Submit</button>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#requestStatusModal">Request Status</button>
            </div>
        </form>
    </x-container>

    <div class="modal fade" id="requestStatusModal" tabindex="-1" aria-labelledby="requestStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="requestStatusModalLabel">Request Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row px-3 pb-1 gap-2 fs-5">
                        <div class="col rounded bg-secondary-subtle">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkStatus">
                                <label class="form-check-label ps-2 fs-5" for="checkStatus">
                                    Process 1 - Marketing
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <a href="javascript:void(0);">Attachment</a>
                        </div>
                    </div>
                    <div class="row px-3 pb-1 gap-2 fs-5">
                        <div class="col rounded bg-primary-subtle">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkStatus">
                                <label class="form-check-label ps-2" for="checkStatus">
                                    Process 2 - Sales
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <a href="javascript:void(0);">Attachment</a>
                        </div>
                    </div>
                    <div class="row px-3 pb-1 gap-2 fs-5">
                        <div class="col rounded bg-secondary-subtle">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkStatus">
                                <label class="form-check-label ps-2 fs-5" for="checkStatus">
                                    Process 3 - Marketing
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <a href="javascript:void(0);">Attachment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $('.single-check').on('change', function() {
                $('.single-check').not(this).prop('checked', false);

                const selectedType = $(this).val();
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('request_id', selectedType);
                $.ajax({
                    url: "{{ route('requisition.other.details') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        console.log('res: ', res);
                    }, error: function (xhr) {
                        console.error('error: ', error);
                    }
                })
            });

            function fetchPRFDetails(type) {
                
            }
        });
    </script>
@endpush