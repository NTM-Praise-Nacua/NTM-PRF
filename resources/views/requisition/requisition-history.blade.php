@extends('layouts.app')

@push('css')
    <style>
        .highlight-assign td {
            background: #FFE28A !important;
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="PRF History">
        <a href="{{ route('requisition.form') }}" class="btn btn-sm btn-primary float-end">Add Request</a>

        <div class="">
            <table id="prf-table" class="table table-hover no-wrap">
                <thead>
                    <tr>
                        <td style="width: 3%">No</td>
                        <td>Request Type</td>
                        <td>Date Requested</td>
                        <td>Date Needed</td>
                        <td>Status</td>
                        <td>Requested By</td>
                        <td>Contact</td>
                        <td>Position</td>
                        <td>Department</td>
                        <td>Branch</td>
                        <td>Urgency</td>
                        <td>Request Details</td>
                        <td>Current Assigned EE</td>
                        <td>Actions</td>
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
                    if (data.assigned_employee.id === {{ auth()->user()->id }}) {
                        $(row).addClass('highlight-assign');
                    }
                }
            });
        });
    </script>
@endpush