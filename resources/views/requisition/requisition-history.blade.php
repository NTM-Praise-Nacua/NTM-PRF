@extends('layouts.app')

@push('css')
    <style>
        .highlight-pending td {
            background: #FFF9C4 !important;
        }
        .highlight-approved td {
            background: #C8E6C9 !important;
        }
        .highlight-rejected td {
            background: #FFCDD2 !important;
        }
        .highlight-inprogress td {
            background: #69BFFF !important;
        }
        .highlight-executed td {
            background: #B2EBF2 !important;
        }
        .highlight-completed td {
            background: #28A745 !important;
            color: white !important;
        }
        .single-line-column {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="PRF History">
        <div class="">
            @if (auth()->user()->id != 1)
                <a href="{{ route('requisition.form') }}" class="btn btn-sm btn-primary float-end position-relative" style="z-index: 5;">Add Request</a>
            @endif

            <div class="float-end position-relative mx-2" style="z-index: 5;">
                <label for="filter-status" style="white-space: nowrap; text-align:left;">Status: </label>
                <select class="form-select form-select-sm d-inline" name="filter-status" id="filter-status" style="width: auto;">
                    <option value="" selected>All</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="2">Rejected</option>
                    <option value="3">In Progress</option>
                    <option value="4">Executed</option>
                    <option value="5">Completed</option>
                </select>
            </div>

            <div class="float-end position-relative mx-2" style="z-index: 5;">
                <label for="filter-status" style="white-space: nowrap; text-align:left;">Date Requested From: </label>
                <input type="date" class="form-control form-control-sm d-inline" name="filter-date_requested" id="filter-date_requested" style="width: auto;" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>

            @if (auth()->user()->id != 1)
                <div class="float-end position-relative mx-2" style="z-index: 5;">
                    <select class="form-select form-select-sm d-inline" name="filter-formsby" id="filter-formsby">
                        <option value="1">Others</option>
                        <option value="0">My PRF</option>
                    </select>
                </div>
            @endif

            <table id="prf-table" class="table table-striped table-hover no-wrap my-2 w-100">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Request Type</th>
                        <th class="text-center">Date Requested</th>
                        <th class="text-center">Date Needed</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Requested By</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Branch</th>
                        <th class="text-center">Urgency</th>
                        <th class="text-center">Request Details</th>
                        <th class="text-center">Assigned Employee</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </x-container>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let table = $('#prf-table').DataTable({
                processing: true,
                serverSide: true,
                dom: '<"top-left"f>rtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: {
                    url: "{{ route('requisition.list') }}",
                    data: function (d) {
                        d.status = $('#filter-status').val();
                        d.date_requested = $('#filter-date_requested').val();
                        d.forms_by = $('#filter-formsby').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'request_type'},
                    {data: 'date_request'},
                    {data: 'date_needed'},
                    {data: 'status'},
                    {data: 'request_by'},
                    {data: 'department'},
                    {data: 'branch'},
                    {data: 'urgency'},
                    {data: 'request_details'},
                    {data: 'assign_employee'},
                    {data: 'actions', orderable: false, searchable: false}
                ],
                columnDefs: [
                    {
                        type: "string",
                        targets: 6
                    },
                    {
                        width: "200px",
                        targets: [1,6]
                    },
                    {
                        width: "150px",
                        targets: [2,3,5,9,10]
                    },
                    {
                        width: "100px",
                        targets: [4,7,8,10]
                    },
                    {
                        className: 'single-line-column', targets: 9
                    }
                ],
                rowCallback: function(row, data, index) {
                    if (data.status == "Pending" && data.requestor_id != {{ auth()->user()->id }}) {
                        $(row).addClass('highlight-pending');
                    } else if (data.status == "Approved") {
                        $(row).addClass('highlight-approved');
                    } else if (data.status == "Rejected") {
                        $(row).addClass('highlight-rejected');
                    } else if (data.status == "In Progress") {
                        $(row).addClass('highlight-inprogress');
                    } else if (data.status == "Executed") {
                        $(row).addClass('highlight-executed');
                    } else if (data.status == "Completed") {
                        $(row).addClass('highlight-completed');
                    }

                    var cell0 = $('td:eq(0)', row).css('padding', '15px');
                    var cell1 = $('td:eq(1)', row).css('padding', '15px');
                    var cell2 = $('td:eq(2)', row).css('padding', '15px');
                    var cell3 = $('td:eq(3)', row).css('padding', '15px');
                    var cell4 = $('td:eq(4)', row).css('padding', '15px');
                    var cell5 = $('td:eq(5)', row).css('padding', '15px');
                    var cell6 = $('td:eq(6)', row).css('padding', '15px');
                    var cell7 = $('td:eq(7)', row).css('padding', '15px');
                    var cell8 = $('td:eq(8)', row).css('padding', '15px');
                    var cell9 = $('td:eq(9)', row).css('padding', '15px');
                    var cell10 = $('td:eq(10)', row).css('padding', '15px');
                    var cell11 = $('td:eq(11)', row).css('padding', '15px');
                    var cell12 = $('td:eq(12)', row).css('padding', '15px');
                    var cell13 = $('td:eq(13)', row).css('padding', '15px');
                }
            });

            $('#filter-status, #filter-date_requested, #filter-formsby').on('change', function () {
                tableDraw();
            });

            function tableDraw() {
                table.draw();
            }

            $(document).on('click', '.btn-assign', function() {
                const requisitionId = $(this).data('requisition-id')
                const userId = @json(auth()->user()->id);
                assignEmployee(userId, requisitionId);
            });

            $(document).on('click', '.btn-assignto', async function() {
                const requisitionId = $(this).data('requisition-id');

                const parentEl = $(this).parent();
                parentEl.css({
                    position: 'relative',
                });

                $(this).css('display', 'none');

                // xmark button
                const btnXWrapper = $('<div></div>')
                .css({
                    width: 20,
                    height: 20,
                    padding: 0,
                    background: 'white',
                    position: 'absolute',
                    top: 2,
                    left: 2,
                    border: "1px solid lightgray",
                    textAlign: "center",
                    display: 'inline-block',
                    lineHeight: 0.85,
                    borderRadius: '50%'
                });
                const closeBtn = $('<a></a>', {
                    href: 'javascript:void(0);',
                    class: 'close-btn',
                    html: '&times;'
                })
                .css({
                    textDecoration: 'none',
                    fontSize: 20,
                    fontWeight: 'bold',
                    color: 'red'
                });
                btnXWrapper.append(closeBtn);

                // checkmark button
                const btnCheckWrapper = $('<div></div>')
                .css({
                    width: 20,
                    height: 20,
                    padding: 0,
                    background: 'white',
                    position: 'absolute',
                    top: 2,
                    right: 2,
                    border: "1px solid lightgray",
                    textAlign: "center",
                    display: 'inline-block',
                    lineHeight: 0.85,
                    borderRadius: '50%'
                });
                const checkBtn = $('<a></a>', {
                    href: 'javascript:void(0);',
                    class: 'check-btn',
                    html: '&check;',
                    "data-prfid": requisitionId,
                })
                .css({
                    textDecoration: 'none',
                    fontSize: 14,
                    fontWeight: 'bold',
                    color: 'green',
                    lineHeight: 1.45
                });
                btnCheckWrapper.append(checkBtn);

                const selectEl = $('<select></select', {
                    class: 'form-select',
                    name: 'assignto'
                });
                
                const users = await getDepartmentMembers(requisitionId);
                const hiddenOpt = $('<option></option>', {
                    text: 'Assign EE',
                    selected: true,
                    hidden: true,
                    value: '',
                });
                const selfOpt = $('<option></option>', {
                    text: 'Assign to Self',
                    value: @json(auth()->user()->id)
                });
                selectEl.append(hiddenOpt, selfOpt);

                users.forEach(user => {
                    const opt = $('<option></option>', {
                        text: user.first_name + " " + user.last_name.toUpperCase().slice(0,1),
                        value: user.id,
                    });
                    selectEl.append(opt);
                });

                parentEl.append(btnXWrapper, btnCheckWrapper, selectEl);
            });

            async function getDepartmentMembers(requisitionId) {
                return new Promise((resolve, reject) => {
                    const token = $('meta[name="csrf-token"]').attr('content');
                    const formData = new FormData();
                    formData.append('_token', token);
                    formData.append('requisition_id', requisitionId);
                    
                    $.ajax({
                        url: "{{ route('department.getMembers') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            const res = JSON.parse(response);
                            resolve(res.users);
                        },
                        error: function (xhr) {
                            reject('error: ', xhr.responseText);
                        }
                    })
                })
            }

            $(document).on('click', '.close-btn', function() {
                const parentEl = $(this).closest('td');
                const selectEl = parentEl.find('select[name="assignto"]');
                selectEl.remove();

                const btnAssign = $('.btn-assignto');
                btnAssign.css('display', 'inline-block');
                $(this).parent().remove();
            });

            $(document).on('click', '.check-btn', function() {
                const select = $(this).closest('td').find('select[name="assignto"]');
                if (select.val() == "") {
                    alertMessage('Please Select Employee', 'warning')
                } else {
                    assignEmployee(select.val(), $(this).data('prfid'));
                }
            });

            function assignEmployee(empId, prfId) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('user_id', empId);
                formData.append('prf_id', prfId);

                $.ajax({
                    url: "{{ route('requisition.assign') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);

                        alertMessage(res.message, res.status, '', false, null, true);

                        if (res.status === 'success') {
                            setTimeout(() => {
                                tableDraw();
                            }, 1500);
                        }
                    }, error: function (xhr) {
                        console.error('error: ', xhr);
                    }
                });
            }
        });
    </script>
@endpush