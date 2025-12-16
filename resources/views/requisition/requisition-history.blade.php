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
                <input type="date" class="form-control form-control-sm d-inline" name="filter-date_requested" id="filter-date_requested" style="width: auto;" value="{{ date('Y-m-d') }}">
            </div>

            <table id="prf-table" class="table table-striped table-hover no-wrap my-2">
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

            $('#filter-status, #filter-date_requested').on('change', function () {
                table.draw();
            });
        });
    </script>
@endpush