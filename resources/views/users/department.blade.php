@extends('layouts.app')

@section('content')
    
<x-container pageTitle="Department List">
    <div class="d-flex w-100 gap-3 mt-2">
        <div class="col-9">
            <table id="department-table" class="table table-hover">
                <thead>
                    <tr>
                        <td style="width: 5%;">No.</td>
                        <td>Name</td>
                        <td>Short Name</td>
                        <td>Created By</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">No data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-3">
            <div class="card bg-white shadow-sm">
                <div class="card-body p-3">
                    <h3>Add Department</h3>
                    <form action="{{ route('department.add') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-white" name="name" id="name" placeholder="Department Name">
                            <label for="name">Department Name (ex. "Human Resources")</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-white" name="shortcut" id="shortcut" placeholder="Shortcut">
                            <label for="shortcut">Shortcut Name (ex. "HR")</label>
                        </div>
                        <button class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection

@push('js')
    <script>
        $(function() {
            $('#department-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('department.data') }}",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'shortcut'},
                    {data: 'created_by'},
                    {data: 'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endpush