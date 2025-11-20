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
        <form action="" class="my-5">
            <h2 class="text-center">PURCHASE REQUISITION FORM</h2>
            <div class="row mb-3">
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="date_request" class="form-field__label">Date & Time requested</label>
                        <input type="text" name="date_request" id="date_request" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="date" name="date_request" id="date_request" class="form-control bg-white" placeholder="Date & Time requested">
                        <label for="date_request">Date & Time requested</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="date_needed" class="form-field__label">Date Needed</label>
                        <input type="text" name="date_needed" id="date_needed" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="date" name="date_needed" id="date_needed" class="form-control bg-white" placeholder="Date Needed">
                        <label for="date_needed">Date Needed</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="status" class="form-field__label">Status</label>
                        <input type="text" name="status" id="status" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="status" id="status" class="form-control bg-white" placeholder="Status">
                        <label for="status">Status</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="full_name" class="form-field__label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="full_name" id="full_name" class="form-control bg-white" placeholder="Full Name">
                        <label for="full_name">Full Name</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="contact" class="form-field__label">Contact Num</label>
                        <input type="text" name="contact" id="contact" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="contact" id="contact" class="form-control bg-white" placeholder="Contact Num">
                        <label for="contact">Contact Num</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="position" class="form-field__label">Position</label>
                        <input type="text" name="position" id="position" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="position" id="position" class="form-control bg-white" placeholder="Position">
                        <label for="position">Position</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="department" class="form-field__label">Department</label>
                        <input type="text" name="department" id="department" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="department" id="department" class="form-control bg-white" placeholder="Department">
                        <label for="department">Department</label>
                    </div>
                </div>
                <div class="col">
                    {{-- <div class="form-group">
                        <label for="branch" class="form-field__label">Branch</label>
                        <input type="text" name="branch" id="branch" class="form-field__input">
                    </div> --}}
                    <div class="form-floating">
                        <input type="text" name="branch" id="branch" class="form-control bg-white" placeholder="Branch">
                        <label for="branch">Branch</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select name="urgency" id="urgency" class="form-select bg-white">
                            <option value="" selected hidden>Select Level</option>
                            <option value="test">test</option>
                            <option value="test">test</option>
                        </select>
                        <label for="urgency">Urgency</label>
                    </div>
                </div>
            </div>

            <x-section-head headTitle="Type of Request"></x-section-head>

            <div class="request-lists container my-3 d-flex flex-column flex-wrap gap-2 overflow-x-auto">
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type1" class="form-check-input rounded-0">
                    <label for="request_type1" class="fs-5 ms-2">Office Supply</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type2" class="form-check-input rounded-0">
                    <label for="request_type2" class="fs-5 ms-2">Safety Equipment</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type3" class="form-check-input rounded-0">
                    <label for="request_type3" class="fs-5 ms-2">Marketing Collaterals</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type4" class="form-check-input rounded-0">
                    <label for="request_type4" class="fs-5 ms-2">Store Dress Up</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type5" class="form-check-input rounded-0">
                    <label for="request_type5" class="fs-5 ms-2">IT Equipment</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type6" class="form-check-input rounded-0">
                    <label for="request_type6" class="fs-5 ms-2">Utility Supplies</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type7" class="form-check-input rounded-0">
                    <label for="request_type7" class="fs-5 ms-2">Signage Request</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type8" class="form-check-input rounded-0">
                    <label for="request_type8" class="fs-5 ms-2">Event & Sponsorship</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type9" class="form-check-input rounded-0">
                    <label for="request_type9" class="fs-5 ms-2">Calling Card</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type10" class="form-check-input rounded-0">
                    <label for="request_type10" class="fs-5 ms-2">Fixed Assets</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type11" class="form-check-input rounded-0">
                    <label for="request_type11" class="fs-5 ms-2">Vechile Dress Up</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="request_type" id="request_type12" class="form-check-input rounded-0">
                    <label for="request_type12" class="fs-5 ms-2">Reward Items</label>
                </div>
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
                <button type="button" class="btn btn-sm btn-primary">Request Status</button>
            </div>
        </form>
    </x-container>
@endsection