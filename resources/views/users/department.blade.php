@extends('layouts.app')

@section('content')
    
<x-container pageTitle="Department List">
    
    <div>
        <button class="btn btn-sm btn-primary float-end add-btn" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">Add</button>
        <table id="department-table" class="table table-hover table-striped my-2">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">No data.</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-container>

<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addDepartmentModalLabel">Add Department</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDepartment" action="{{ route('department.add') }}" method="POST">
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

<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editDepartmentModalLabel">Edit Department</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-department" method="POST">
                    @csrf
                    <input type="hidden" name="departmentId">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control bg-white" name="name" id="name" placeholder="Department Name">
                        <label for="name">Department Name (ex. "Human Resources")</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control bg-white" name="shortcut" id="shortcut" placeholder="Shortcut">
                        <label for="shortcut">Shortcut Name (ex. "HR")</label>
                    </div>
                    <button class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(function() {
            $('#department-table').DataTable({
                processing: true,
                serverSide: true,
                dom: '<"top-left"f>rtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('department.data') }}",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'shortcut'},
                    {data: 'created_by'},
                    {data: 'actions', orderable: false, searchable: false}
                ],
                columnDefs: [
                    {
                        width: "200px",
                        targets: [1]
                    },
                    {
                        width: "150px",
                        targets: [2,3]
                    },
                    {
                        width:"50px",
                        targets: [0,4]
                    }
                ]
            });

            $('#addDepartment').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('department.add') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);

                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        alertMessage('Successfully Added!', 'success');

                    }, error: function (xhr) {
                        if (xhr.status === 500) {
                            console.error('error: ', xhr.responseText);
                            alertMessage("Something went wrong!", "error");
                        } else {
                            displayErrorFields(xhr.responseJSON?.errors, e.currentTarget.id);
                        }
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                fetchDepartmentData(id);
            });

            function fetchDepartmentData(id) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('id', id);
                formData.append('_token', token);

                $.ajax({
                    url: '{{ route("department.details.get") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status == 'success') {
                            const form = $('#edit-department');
                            form.find('input:text, input:password, input[type=email], textarea, select').val('');
                            const hiddenInput = form.find('input[name="departmentId"]');

                            const name = form.find('input[name="name"]');
                            const shortcut = form.find('input[name="shortcut"]');
                            
                            hiddenInput.val(res.data.id);
                            name.val(res.data.name);
                            shortcut.val(res.data.shortcut);

                            const modalEl = document.getElementById('editDepartmentModal');
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, error: function (xhr) {
                        console.error("error: ", xhr);
                    }
                });
            }

            $('#edit-department').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("department.update") }}',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        alertMessage(res.message, res.status);

                    },
                    error: function(xhr, error) {
                        if (xhr.status === 500) {
                            console.error('error: ', xhr.responseText);
                            alertMessage("Something went wrong!", "error");
                        } else {
                            displayErrorFields(xhr.responseJSON?.errors, e.currentTarget.id);
                        }
                    }
                });
            });

            function displayErrorFields(errorObj, formId) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errorObj, function(field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');

                    input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                });
            }

            $(document).on('click', '.add-btn, .edit-btn', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });
        });
    </script>
@endpush