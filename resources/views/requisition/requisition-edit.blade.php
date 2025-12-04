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
            background-color: #198754;
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
    $user = auth()->user();
    $counter = 0;
    $isBetween = false;

    $currentStep = null;
    $steps = [];

    foreach ($PRFWorkflow as $index => $item) {
        $trackerExists = isset($tracker[$index]);
        $rawSubmittedAt = $trackerExists ? $tracker[$index]['submitted_at'] : null;

        if (!$trackerExists) {
            $status = '';
        } elseif ($rawSubmittedAt === null) {
            $status = $requisition->status == 2 ? 'rejected' : 'pending';
            $currentStep = $requisition->status == 2 ? $PRFWorkflow[$index - 1]['id'] : $item->id;
        } else {
            $status = 'completed';
        }

        if ($status === 'completed') {
            $displayDate = (new DateTime($rawSubmittedAt))->format("D, F j");
        } elseif ($status === 'pending') {
            $displayDate = date("D, F j");
        } else {
            $displayDate = '---';
        }

        $steps[] = [
            'name'  => $item->department->shortcut ?? '---',
            'date'  => $displayDate,
            'status'=> $status,
        ];

        if ($status == "pending") {
            $isFirst = ($index == 0);
            $isLast = ($index == count($PRFWorkflow) - 1);

            if (!$isFirst && !$isLast) {
                $isBetween = true;
            }
        }
    }

    $nextIndex = array_key_last($tracker) + ($requisition->status == 2 ? 0 : 1);
    $nextDepartment = optional($PRFWorkflow->get($nextIndex))?->toArray();

    $nextDepId = $nextDepartment ? $nextDepartment['ordering'] : null;
@endphp

@section('content')
    <x-container>
        <div class="container my-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                @foreach($steps as $index => $step)
                    <div class="d-flex align-items-center flex-grow-1 position-relative step-container">
                        <div class="step-circle {{ $step['status'] == 'completed' ? 'completed' : ($step['status'] == 'pending' ? 'pending' : ($step['status'] == 'rejected' ? 'bg-danger' : '')) }}">
                            @if($step['status'] == 'completed')
                                &#10003;
                            @elseif ($step['status'] == 'rejected')
                                &#10007;
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div>
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
        <form action="{{ route('requisition.form.update', $requisition->id) }}" method="POST" class="my-5 needs-validation" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h2 class="text-center">PURCHASE REQUISITION FORM</h2>
            <input type="hidden" name="workflow_step_id" value="{{ $currentStep }}">
            <div class="row mb-3">
                <div class="col">
                    <div class="form-floating">
                        <input type="date" name="date_request" id="date_request" class="form-control bg-white @error('date_request') is-invalid @enderror" value="{{ $requisition->date_request->toDateString() }}" placeholder="Date & Time requested" disabled>
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
                        <input type="date" name="date_needed" id="date_needed" class="form-control bg-white @error('date_needed') is-invalid @enderror" value="{{ $requisition->date_needed->toDateString() }}" placeholder="Date Needed" disabled>
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
                        <select id="formStat" name="selectstatus" disabled="disabled" aria-required="true" aria-invalid="false" class="form-select @error('status') is-invalid @enderror">
                            <option value="0" {{ $requisition->status === 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $requisition->status === 1 ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ $requisition->status === 2 ? 'selected' : '' }}>Rejected</option>
                            <option value="3" {{ $requisition->status === 3 ? 'selected' : '' }}>Executed</option>
                            <option value="4" {{ $requisition->status === 4 ? 'selected' : '' }}>Confirmed</option>
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
                        <input type="text" name="full_name" id="full_name" class="form-control bg-white @error('full_name') is-invalid @enderror" placeholder="Full Name" value="{{ $requisition->full_name }}" disabled>
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
                        <input type="text" name="contact" id="contact" class="form-control bg-white @error('contact') is-invalid @enderror" placeholder="Contact Num" value="{{ $requisition->contact }}" disabled>
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
                        <input type="text" name="position_id" id="position" class="form-control bg-white @error('position') is-invalid @enderror" placeholder="Position" value="{{ $requisition->positionName->name }}" disabled>
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
                        <input type="text" name="department_id" id="department" class="form-control bg-white @error('department') is-invalid @enderror" placeholder="Department" value="{{ $requisition->departmentName->name }}" disabled>
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
                        <input type="text" name="branch" id="branch" class="form-control bg-white @error('branch') is-invalid @enderror" placeholder="Branch" value="{{ $requisition->branch }}" disabled>
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
                        <select name="urgency" id="urgency" class="form-select bg-white @error('urgency') is-invalid @enderror" disabled>
                            <option value="" {{ $requisition->urgency == "" ? 'selected' : '' }} hidden>Select Level</option>
                            <option value="Highest" {{ $requisition->urgency == "Highest" ? 'selected' : '' }}>Highest (1-2 Hours)</option>
                            <option value="High" {{ $requisition->urgency == "High" ? 'selected' : '' }}>High (1-2 Days)</option>
                            <option value="Medium" {{ $requisition->urgency == "Medium" ? 'selected' : '' }}>Medium (3-6 Days)</option>
                            <option value="Low" {{ $requisition->urgency == "Low" ? 'selected' : '' }}>Low (1-2 Weeks)</option>
                            <option value="Lowest" {{ $requisition->urgency == "Lowest" ? 'selected' : '' }}>Lowest (3-4 Weeks)</option>
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
                        <input type="checkbox" name="request_type" id="request_type{{ $counter }}" class="form-check-input rounded-0 single-check @error('request_type') is-invalid @enderror" value="{{ $item->id }}" {{ $requisition->request_type == $item->id ? 'checked' : '' }} disabled>
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
                <textarea name="request_details" class="form-control @error('request_details') is-invalid @enderror" placeholder="Request Details" id="request_details" style="height: 130px" disabled>{{ $requisition->request_details }}</textarea>
                <label for="request_details">Request Details</label>
                <div class="invalid-feedback d-block">
                    @error('request_details')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="inline-block fs-5 fw-bold">Attachment(s)</label>
                <div class="d-flex">
                    <div class="col-3">
                        <div class="attachment-list p-1d-flex flex-column flex-wrap gap-2" style="max-height: 100px">
                            @forelse ($attachments as $attachment)
                                <div>
                                    <a href="javascript:void(0);" data-src="{{ asset('storage/'. $attachment->path) }}">{{ $attachment->original_name }}</a>
                                </div>
                            @empty
                                No attachments...
                            @endforelse
                        </div>
                    </div>
                    <div class="col viewPDF"></div>
                </div>
            </div>
            <div class="upload-pdf-group row mb-3 {{ ($user->id == $requisition->request_by || $user->role_id == 1 || in_array($requisition->status, [0, 4]) || ($requisition->status == 2 && $user->id != $requisition->assign_employee) || ($requisition->assign_employee != $user->id && $requisition->status == 3)) ? 'd-none' : '' }}">
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
                        <input type="hidden" name="department_id" value="{{ $nextDepId }}" {{ $nextDepId ?? 'disabled' }}>
                        <select name="next_department" id="next_department" class="form-select form-select-sm w-75 bg-white @error('next_department') is-invalid @enderror" disabled>
                            <option value="" {{ $nextDepartment == null ? 'selected' : '' }} hidden>Select Department</option>
                            @forelse ($departments as $item)
                                <option value="{{ $item->id }}" {{ (($nextDepId ?? $requisition->department) == $item->id) ? 'selected' : '' }}>{{ $item->shortcut }}</option>
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
                        <select name="assign_employee" id="assign_employee" class="form-select form-select-sm w-75 bg-white @error('assign_employee') is-invalid @enderror" {{ $nextDepId ?? 'disabled' }}>
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

            @if (($isBetween && $user->id != $requisition->request_by) || ($requisition->status == 2 && $user->id == $requisition->assign_employee))
            <div class="mb-3">
                <h5>Remarks <span class="fs-6 fw-normal fst-italic">(optional)</span></h5>
                <div class="form-floating w-50" style="min-width: 300px;">
                    <textarea id="remarks" name="remarks"  class="form-control" placeholder="Remarks" style="height: 130px"  {{ $requisition->status == 2 && $user->id == $requisition->assign_employee ? 'disabled' : '' }}>{{ $requisition->remarks }}</textarea>
                    <label for="remarks">Remarks</label>
                </div>
            </div>
            @endif

            <x-section-head headTitle="FOR APPROVER"></x-section-head>

            <h6 class="my-3">LIST OF APPROVERS</h6>
            <div class="d-flex flex-wrap">
                <div class="rounded-2 bg-info-subtle text-primary px-2 py-1">{{ $approver?->name }}</div>
            </div>

            <hr>

            <div class="button-group float-end">
                @if ($user->id == $approver->id && $requisition->status == 0)
                    <button type="button" class="btn btn-sm btn-primary approve-btn">Approve</button>
                    <button type="button" class="btn btn-sm btn-danger reject-btn">Reject</button>
                @else
                    @if ($requisition->status == 4 && $user->id == $requisition->request_by)
                        <button type="button" class="btn btn-sm btn-success complete-btn">Complete</button>
                    @else
                        @if ($isBetween && $user->id != $requisition->request_by)
                            <button type="button" class="btn btn-sm btn-danger reject-btn">Reject</button>
                        @endif
                        <button type="submit" class="btn btn-sm btn-primary" {{ ($user->id == $requisition->request_by || $requisition->assign_employee != $user->id || ($requisition->assign_employee == $user->id && $requisition->status == 0)) ? 'disabled' : '' }}>Submit</button>
                    @endif
                @endif
                <button type="button" class="btn btn-sm btn-primary requestStatus">Request Status</button>
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
                    <div class="accordion" id="requestStatusContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            $('.complete-btn').on('click', function() {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('id', {{ $requisition->id }});
                formData.append('status', 5);
                $.ajax({
                    url: "{{ route('requisition.status.update') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        
                        alertMessage(res.message, res.status, "{{ route('requisition.history') }}");
                    }, error: function (xhr) {
                        alertMessage('Something Went Wrong!', 'error');
                        console.error('error: ', xhr.responseText);
                    }
                })
            })

            getEmployees();

            function getEmployees() {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('department_id', {{ $nextDepId ?? $requisition->department }});
                formData.append('requestor_id', {{ $requisition->request_by }});
                formData.append('requisition_id', {{ $requisition->id }});
                console.log('request by: ', {{ $requisition->request_by }});

                $.ajax({
                    url: "{{ route('requisition.employee.department') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        const employee = res.data;
                        const rejector = res.rejector;
                        const formStatus = {{ $requisition->status }};
                        console.log("rejector: ", rejector);

                        const empSelect = $('select[name="assign_employee"]');
                        
                        const hiddenOpt = $('<option></option>');
                        hiddenOpt.val("")
                            .text("Select Employee")
                            .attr('selected', true)
                            .attr('hidden', true);
                        empSelect.append(hiddenOpt);

                        employee.forEach(item => {
                            const opt = $('<option></option>', {
                                value: item.id,
                                text: item.name,
                                selected: ((rejector == item.id && formStatus == 2) ? true : false)
                            });
                            empSelect.append(opt);
                        });
                        
                        if (rejector && formStatus == 2) {
                            empSelect.prop('disabled', true);
                            const form = empSelect.closest('form');
                            const hiddenInput = $('<input>', {
                                type: "hidden",
                                name: "assign_employee",
                                value: rejector
                            });
                            form.append(hiddenInput);
                        }
                    }, error: function (xhr) {
                        console.error('error: ', xhr);
                    }
                });
            }

            let requestModalContent = $('#requestStatusContainer');

            $('.requestStatus').on('click', function() {
                const statusModal = $('#requestStatusModal .modal-dialog')
                    .removeClass('modal-xl');
                let modalBody = $('#requestStatusModal .modal-body').empty();
                modalBody.append(requestModalContent);

                fetchRequisitionProcessStatus();
            });

            function fetchRequisitionProcessStatus() {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('id', {{ $requisition->id }});
                $.ajax({
                    url: '{{ route("requisition.request.status") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);

                        console.log('response: ', res);

                        createRequestStatusElements(res.data.departments, res.data.files);

                        const modalEl = document.getElementById('requestStatusModal');
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    },
                    error: function (xhr) {
                        console.error('Error: ', xhr.responseText);
                    }
                });
            }

            function createRequestStatusElements(departments, files) {
                const wrapper = $('#requestStatusContainer');
                wrapper.empty();
                
                departments.forEach((name, index) => {
                    const itemEl = $('<div></div>').addClass("accordion-item");
                    
                    const textHeadEl = $('<h2></h2>', {
                        class: "accordion-header",
                    });
                    const buttonEl = $('<button></button>', {
                        type: "button",
                        text: `Process ${index+1} ${name}`,
                        class: "accordion-button collapsed",
                        'data-bs-toggle': "collapse",
                        'data-bs-target': `#collapse_${index}`,
                        'aria-expanded': false,
                        'aria-controls': `collapse_${index}`
                    });
                    textHeadEl.append(buttonEl);

                    const bodyWrapper = $('<div></div>', {
                        id:`collapse_${index}`,
                        class:"accordion-collapse collapse",'data-bs-parent':"#requestStatusContainer"
                    });
                    const bodyEl = $('<div></div>', {
                        text: !files[index] ? "No attachments yet." : "",
                        class: `accordion-body ${!files[index] ? "fst-italic" : "fs-6"}`
                    });
                    
                    if (files[index]) {
                        files[index]?.forEach((file) => {
                            const link = $('<a></a>', {
                                href: "javascript:void(0);",
                                'data-src': `{{ asset('storage') }}/${file.path}`,
                                text: file.original_name,
                                class: 'attachment-item d-block',
                            });
                            bodyEl.append(link);
                        })
                    }

                    bodyWrapper.append(bodyEl);

                    wrapper.append(itemEl.append(textHeadEl, bodyWrapper));
                });
            }

            $('.attachment-list a').on('click', function(e) {
                const linkEl = e.currentTarget;
                const linkName = linkEl.innerText;
                const modalLabel = $('#requestorViewAttachLabel');
                const modalViewAttach = $('#requestorViewAttach .modal-body');
                modalLabel.text(linkName);

                const modalEl = document.getElementById('requestorViewAttach');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                viewPDF(linkEl, modalViewAttach, '800px');
            });

            $(document).on('click', '.attachment-item', function(e) {
                e.preventDefault();
                viewPDFModal(e.currentTarget);
            });
            
            function viewPDFModal(linkEl) {
                const statusModal = $('#requestStatusModal .modal-dialog')
                    .css({
                        transition: "all 0.15s ease-in-out"
                    })
                    .addClass('modal-xl');

                const accordion = $('#requestStatusContainer');
                const modalBody = $('#requestStatusModal .modal-body');
                modalBody.empty();

                const flexContainer = $('<div class="d-flex"></div>');
                const accordCol = $('<div class="col-4"></div>');
                const pdfViewCol = $('<div></div>', {
                    class: "col viewPDFModal overflow-auto"
                });
                accordCol.append(accordion);
                modalBody.append(flexContainer.append(accordCol, pdfViewCol));

                viewPDF(linkEl, $('.viewPDFModal'), '1000px');
            }

            $('.approve-btn').on('click', function() {
                approveOrReject('Approved');
            });

            $('.reject-btn').on('click', function() {
                const isBetween = @json($isBetween);

                if (isBetween) {
                    const form = $(this).closest('form');
                    const inputStatus = $('<input>', {
                        hidden: true,
                        name: "status",
                        value: "reject",
                    });
                    form.append(inputStatus);
                    form.submit();
                } else {
                    approveOrReject('Reject');
                }
            });

            function approveOrReject(status) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('id', {{ $requisition->id }});
                formData.append('status', status);
                $.ajax({
                    url: '{{ route("requisition.approve.reject") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);

                        if (res.status) {
                            const statusMessage = `PRF ${status == "Approved" ? "Approved" : "Rejected"}`;
                            alertMessage(statusMessage, res.status, '{{ route("requisition.history") }}');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error: ', xhr.responseText);
                    }
                });
            }
        });
    </script>
@endpush