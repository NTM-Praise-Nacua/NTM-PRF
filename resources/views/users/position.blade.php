@extends('layouts.app')

@section('content')
    <x-container pageTitle=" Position List">
        
        <div class="">
            <button class="btn btn-sm btn-primary float-end position-relative z-1" data-bs-toggle="modal" data-bs-target="#addPositionModal">Add</button>
            <table id="positions-table" class="table table-hover table-striped my-2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Position</th>
                        <th>Created By</th>
                        <th>Date Added</th>
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

    <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPositionModalLabel">Add Position</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPosition" action="{{ route('position.add') }}" method="post">
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

    <div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPositionModalLabel">Edit Position</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-position" method="POST">
                        @csrf
                        <input type="hidden" name="positionId">
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="name" 
                                id="name" placeholder="Position Name">
                                <label for="name">Position Name</label>
                            </div>
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
            $('#positions-table').DataTable({
                processing: true,
                serverSide: true,
                dom: '<"top-left"f>rtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('position.data') }}",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'created_by'},
                    {data: 'created_at'},
                    {data: 'actions', orderable: false, searchable: false}
                ],
                columnDefs: [
                    {
                        width: "300px",
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

            $('#addPosition').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('position.add') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // const res = JSON.parse(response);

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

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                fetchPositionData(id);
            });

            function fetchPositionData(id) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('id', id);
                formData.append('_token', token);

                $.ajax({
                    url: '{{ route("position.details.get") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status == 'success') {
                            const form = $('#edit-position');
                            form.find('input:text, input:password, input[type=email], textarea, select').val('');
                            const hiddenInput = form.find('input[name="positionId"]');

                            const name = form.find('input[name="name"]');
                            
                            hiddenInput.val(res.data.id);
                            name.val(res.data.name);

                            const modalEl = document.getElementById('editPositionModal');
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, error: function (xhr) {
                        console.error("error: ", xhr);
                    }
                });
            }

            $('#edit-position').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("position.update") }}',
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
        });
    </script>
@endpush