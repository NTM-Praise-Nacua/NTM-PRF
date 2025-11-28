@extends('layouts.app')

@section('content')
    <x-container pageTitle=" Position List">
        <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addPositionModal">+ Add</button>

        <div class="">
            <table id="positions-table" class="table table-hover">
                <thead>
                    <tr>
                        <td style="width: 5%;">No</td>
                        <td>Position</td>
                        <td>Created By</td>
                        <td>Date Added</td>
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
    </x-container>

    <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPositionModalLabel">Add Position</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('position.add') }}" method="post">
                        @csrf
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="name" 
                                id="name" placeholder="Position Name">
                                <label for="name">Position Name</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Position</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('#positions-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('position.data') }}",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'created_by'},
                    {data: 'created_at'},
                    {data: 'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endpush