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

        .step-container {
            min-width: 120px;
            margin-bottom: 1rem;
            flex-direction: column;
            text-align: center;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .step-circle.completed, .step-line.completed {
            background-color: #198754; /* Bootstrap success color */
        }

        .step-circle.pending, .step-line.pending {
            background-color: #f7bc0f;
        }

        .step-line {
            height: 4px;
            background-color: #ddd;
            position: absolute;
            top: 19%;
            left: 70%;
            right: -50%;
            /* z-index: -1; */
            transform: translateY(-50%);
            width: 58%
        }

        @media (max-width: 768px) {
            .d-flex.flex-wrap.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
            }
            .step-line {
                width: 4px;
                height: 40px;
                top: 30px;
                left: 15px;
                right: auto;
                transform: none;
            }
            .step-container {
                margin-bottom: 2rem;
            }
        }
    </style>
@endpush

@php
    $counter = 0;
    $steps = [
        ['name' => '---', 'date' => '---', 'status' => ''],
        ['name' => '---', 'date' => '---', 'status' => ''],
        ['name' => '---', 'date' => '---', 'status' => ''],
        ['name' => '---', 'date' => '---', 'status' => ''],
    ];
@endphp

@section('content')
    <x-container>
        <div class="container my-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center header-flow">
                @foreach($steps as $index => $step)
                    <div class="d-flex align-items-center flex-grow-1 position-relative step-container">
                        <div class="step-circle {{ $step['status'] == 'completed' ? 'completed' : ($step['status'] == 'pending' ? 'pending' : '') }}">
                            @if($step['status'] == 'completed')
                                &#10003;
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div class="ms-2">
                            <div class="fw-bold">{{ $step['name'] }}</div>
                            <small class="text-muted">{{ $step['date'] }}</small>
                        </div>

                        @if(!$loop->last)
                            <div class="step-line flex-grow-1 {{ $step['status'] == 'completed' ? 'completed' : ($step['status'] == 'pending' ? 'pending' : '') }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <hr class="border border-2 border-black">
        <form action="{{ route('requisition.form.add') }}" method="POST" class="my-5 needs-validation" enctype="multipart/form-data">
            @csrf
            <h2 class="text-center">PURCHASE REQUISITION FORM</h2>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="date" name="date_request" id="date_request" class="form-control bg-white @error('date_request') is-invalid @enderror" value="{{ now()->toDateString() }}" placeholder="Date & Time requested">
                        <label for="date_request">Date & Time requested</label>
                        <div class="invalid-feedback">
                            @error('date_request')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="date" name="date_needed" id="date_needed" class="form-control bg-white @error('date_needed') is-invalid @enderror" value="{{ now()->toDateString() }}" placeholder="Date Needed">
                        <label for="date_needed">Date Needed</label>
                        <div class="invalid-feedback">
                            @error('date_needed')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="hidden" name="status" value="0">
                        <select id="formStat" name="selectstatus" disabled="disabled" aria-required="true" aria-invalid="false" class="form-select @error('status') is-invalid @enderror">
                            <option value="0" selected>Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Rejected</option>
                            <option value="3">Executed</option>
                            <option value="4">Confirmed</option>
                        </select>
                        <label for="formStat">Status</label>
                        <div class="invalid-feedback">
                            @error('status')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="hidden" name="request_by" value="{{ auth()->user()->id }}">
                        <input type="text" name="full_name" id="full_name" class="form-control bg-white @error('full_name') is-invalid @enderror" placeholder="Full Name" value="{{ auth()->user()->name }}">
                        <label for="full_name">Full Name</label>
                        <div class="invalid-feedback">
                            @error('full_name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="contact" id="contact" class="form-control bg-white @error('contact') is-invalid @enderror" placeholder="Contact Num" value="{{ auth()->user()->contact_no }}">
                        <label for="contact">Contact Num</label>
                        <div class="invalid-feedback">
                            @error('contact')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="hidden" name="position" class="form-control bg-white" placeholder="Position" value="{{ auth()->user()->position->id }}">
                        <input type="text" name="position_id" id="position" class="form-control bg-white @error('position') is-invalid @enderror" placeholder="Position" value="{{ auth()->user()->position->name }}" disabled>
                        <label for="position_id">Position</label>
                        <div class="invalid-feedback">
                            @error('position')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="hidden" name="department"  class="form-control bg-white" placeholder="Department" value="{{ auth()->user()->department->id }}">
                        <input type="text" name="department_id" id="department" class="form-control bg-white @error('department') is-invalid @enderror" placeholder="Department" value="{{ auth()->user()->department->name }}" disabled>
                        <label for="department_id">Department</label>
                        <div class="invalid-feedback">
                            @error('department')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="text" name="branch" id="branch" class="form-control bg-white @error('branch') is-invalid @enderror" placeholder="Branch" value="Head Office">
                        <label for="branch">Branch</label>
                        <div class="invalid-feedback">
                            @error('branch')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select name="urgency" id="urgency" class="form-select bg-white @error('urgency') is-invalid @enderror">
                            <option value="" selected hidden>Select Level</option>
                            <option value="Highest">Highest (1-2 Hours)</option>
                            <option value="High">High (1-2 Days)</option>
                            <option value="Medium">Medium (3-6 Days)</option>
                            <option value="Low">Low (1-2 Weeks)</option>
                            <option value="Lowest">Lowest (3-4 Weeks)</option>
                        </select>
                        <label for="urgency">Urgency</label>
                        <div class="invalid-feedback">
                            @error('urgency')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <x-section-head headTitle="Type of Request"></x-section-head>

            <div class="request-lists container my-1 d-flex flex-column flex-wrap gap-2 overflow-x-auto">
                @forelse ($requestTypes as $item)
                    <div class="checkbox-group">
                        <input type="checkbox" name="request_type" id="request_type{{ $counter }}" class="form-check-input rounded-0 single-check @error('request_type') is-invalid @enderror" value="{{ $item->id }}">
                        <label for="request_type{{ $counter }}" class="fs-5 ms-2 form-check-label">{{ $item->name }}</label>
                    </div>
                    @php
                        $counter++;
                    @endphp
                @empty
                    <p class="text-center">Error Fetching Request Types</p>
                @endforelse

            </div>
            <div class="container mb-2">
                <div class="invalid-feedback d-block">
                    @error('request_type')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-floating mb-3">
                <textarea name="request_details" class="form-control @error('request_details') is-invalid @enderror" placeholder="Request Details" id="request_details" style="height: 130px"></textarea>
                <label for="request_details">Request Details</label>
                <div class="invalid-feedback d-block">
                    @error('request_details')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="mb-3 d-none">
                <label class="inline-block fs-5 fw-bold">Attachment(s)</label>
                <div class="attachment-list p-1 d-flex flex-column flex-wrap gap-2" style="max-height: 100px">
                    {{-- @forelse ($attachments as $item)
                        <a href="{{ asset('storage/'. $item->path) }}" download>{{ $item->original_name }}</a>
                    @empty
                        No attachments...
                    @endforelse --}}
                </div>
            </div>
            <div class="upload-pdf-group row mb-3">
                <div class="col">
                    <label for="upload_pdf" class="fs-5 fw-bold">Upload PDF</label>
                    <input type="file" name="upload_pdf[]" id="upload_pdf" class="form-control w-75 @error('upload_pdf') is-invalid @enderror" multiple>
                    <div class="invalid-feedback d-block">
                        @error('upload_pdf')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-8 d-flex gap-1">
                    <div class="col">
                        <div class="col d-flex align-items-center">
                            <p class="m-0 fs-5 fw-bold">Accountable Department</p>
                        </div>
                        <input type="hidden" name="next_department">
                        <select name="next_department_id" id="next_department" class="form-select form-select-sm w-75 bg-white @error('next_department') is-invalid @enderror" disabled>
                            <option value="" selected hidden>Select Department</option>
                            @forelse ($departments as $item)
                                <option value="{{ $item->id }}">{{ $item->shortcut }}</option>
                            @empty
                                <option value="">No Request Types Found</option>
                            @endforelse
                        </select>
                        <div class="invalid-feedback d-block">
                            @error('next_department')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="col d-flex align-items-center">
                            <p class="m-0 fs-5 fw-bold">Select Employee</p>
                        </div>
                        <select name="assign_employee" id="assign_employee" class="form-select form-select-sm w-75 bg-white @error('assign_employee') is-invalid @enderror">
                            <option value="" selected hidden>Select Employee</option>
                        </select>
                        <div class="invalid-feedback d-block">
                            @error('assign_employee')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <x-section-head headTitle="FOR APPROVER"></x-section-head>

            <h6 class="my-3">LIST OF APPROVERS</h6>
            <div class="d-flex flex-wrap">
                <div class="rounded-2 bg-info-subtle text-primary px-2 py-1">{{ $approver->name }}</div>
            </div>

            <hr>

            <div class="button-group float-end">
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#requestStatusModal">Request Status</button> --}}
            </div>
        </form>
    </x-container>

    <div class="modal fade" id="requestorViewAttach" tabindex="-1" aria-labelledby="requestorViewAttachLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="requestorViewAttachLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            const headerFlow = $('.header-flow');
            const originalChildren = headerFlow.children().clone(true);


            $('.single-check').on('change', function(e) {
                if (!$(this).prop('checked')) {
                    $(this).prop('checked', true);
                    return;
                }
                $('.single-check').not(this).prop('checked', false);

                const selectedType = $(this).val();
                fetchPRFDetails(selectedType);
            });

            function fetchPRFDetails(type) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('request_id', type);
                formData.append('requestor_id', {{ auth()->user()->id }});
                $.ajax({
                    url: "{{ route('requisition.other.details') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        const department = res.data["department"];
                        const employee = res.data["employee"];

                        updateHeaderFlow(res.data.completeFlow);

                        const depSelect = $('select[name="next_department_id"]');
                        const empSelect = $('select[name="assign_employee"]');
                        depSelect.val(department?.ordering);
                        $('input[name="next_department"]').val(department?.ordering);
                        
                        empSelect.empty();
                        const hiddenOpt = $('<option></option>');
                        hiddenOpt.val("")
                            .text("Select Employee")
                            .attr('selected', true)
                            .attr('hidden', true);
                        empSelect.append(hiddenOpt);
                            
                        employee.forEach(item => {
                            const opt = $('<option></option>');
                            opt.val(item.id);
                            opt.text(item.name);
                            empSelect.append(opt);
                        });

                        showRequestTypeAttachments(res.data['file']);
                    }, error: function (xhr) {
                        console.error('error: ', xhr);
                    }
                });
            }

            function showRequestTypeAttachments(files) {
                const attachParentEl = $('.attachment-list').parent();
                const attachList = $('.attachment-list');
                
                attachList.empty();
                
                if (!files || files.length === 0) {
                    attachList.empty();
                    attachParentEl.addClass('d-none');
                    return;
                }
                
                files.forEach(file => {
                    const link = $('<a></a>', {
                        href: "javascript:void(0);",
                        'data-src': `{{ asset('storage') }}/${file.path}`,
                        text: file.original_name,
                        class: 'attachment-item',
                        click: (e) => viewPDFModal(e.currentTarget)
                    });

                    attachList.append(link);
                });

                attachParentEl.removeClass('d-none');
            }

            function updateHeaderFlow(flow) {

                if (!flow || flow.length <= 0) {
                    headerFlow.empty().append(originalChildren.clone(true));
                    return;
                };
                
                headerFlow.empty();
                
                flow.forEach((item, index) => {
                    const divWrapper = $('<div></div>', {
                        class: 'd-flex align-items-center flex-grow-1 position-relative step-container'
                    });
                    const circle = $('<div></div>', {
                        class: "step-circle",
                        text: index + 1
                    });
                    const depText = $('<div></div>', {
                        class: "ms-2",
                        html: `<div class="fw-bold">${item}</div>
                            <small class="text-muted">---</small>`
                    });
                    divWrapper.append(circle, depText);

                    if (flow.length > index + 1) {
                        const line = $('<div></div>', {
                            class: "step-line flex-grow-1",
                        });

                        divWrapper.append(line);
                    }
                    headerFlow.append(divWrapper);
                });

            }

            function viewPDFModal(linkEl) {
                const linkName = linkEl.innerText;

                const modalLabel = $('#requestorViewAttachLabel');
                const modalViewAttach = $('#requestorViewAttach .modal-body');
                modalLabel.text(linkName);

                const modalEl = document.getElementById('requestorViewAttach');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                viewPDF(linkEl, modalViewAttach, '800px');
            }
        });
    </script>
@endpush