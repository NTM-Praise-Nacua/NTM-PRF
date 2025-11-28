@extends('layouts.app')

@push('css')
    <style>
        .highlight-yellow td {
            background: #FFE28A !important;
        }
        .highlight-green td {
            background: #C1F5C1 !important;
        }
        table th {
            background: #212529 !important;
            color: white !important;
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="PRF History">
        <a href="{{ route('requisition.form') }}" class="btn btn-sm btn-primary float-end">Add Request</a>

        <div class="">
            <table id="prf-table" class="table table-striped table-hover no-wrap">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Request Type</th>
                        <th class="text-center">Date Requested</th>
                        <th class="text-center">Date Needed</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Requested By</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Position</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Branch</th>
                        <th class="text-center">Urgency</th>
                        <th class="text-center">Request Details</th>
                        <th class="text-center">Current Assigned EE</th>
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
            $('#prf-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('requisition.list') }}",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'request_type'},
                    {data: 'date_request'},
                    {data: 'date_needed'},
                    {data: 'status'},
                    {data: 'request_by'},
                    {data: 'contact', orderable: false},
                    {data: 'position'},
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
                    }
                ],
                rowCallback: function(row, data, index) {
                    if (data.status == "Approved") {
                        $(row).addClass('highlight-green');
                    } else if (data.assigned_employee && data.assigned_employee.id === {{ auth()->user()->id }}) {
                        $(row).addClass('highlight-yellow');
                    }

                    var cell0 = $('td:eq(0)', row).css('padding', '15px');
                    var cell1 = $('td:eq(1)', row).css('padding', '5px');
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
        });
    </script>
@endpush