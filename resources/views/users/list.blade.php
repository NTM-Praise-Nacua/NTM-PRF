@extends('layouts.app')

@section('content')
    <x-container pageTitle="User List">
        <button class="btn btn-sm btn-primary float-end add-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">Add</button>

        <div class="">
            <table id="users-table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Roles</th>
                        <th>Date Registered</th>
                        <th>Created by</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">No data.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- <div class="row mt-3">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('role.create') }}" method="POST">
                            @csrf
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="enter role name">
                            <input type="hidden" name="is_active" value="1">
                            <button type="submit" class="btn btn-sm btn-primary">Add Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    </x-container>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Add a User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUser" method="post">
                        @csrf
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="first_name" 
                                id="first_name" placeholder="First Name">
                                <label for="first_name">First Name</label>
                            </div>
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="last_name" 
                                id="last_name" placeholder="Last Name">
                                <label for="last_name">Last Name</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="email" class="form-control" name="email" 
                                id="email" placeholder="Email">
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating col">
                                <input type="password" class="form-control" name="password" 
                                id="password" placeholder="Password">
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="contact" 
                                id="contact" placeholder="Contact">
                                <label for="contact">Contact No</label>
                            </div>
                            <div class="form-floating col">
                                <select name="position" id="position" name="position" class="form-select">
                                    <option value="" selected hidden>Select Position</option>
                                    @forelse ($positions as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="position">Position</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <select name="department" id="department" name="department" class="form-select">
                                    <option value="" selected hidden>Select Department</option>
                                    @forelse ($departments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="department">Department</label>
                            </div>
                            <div class="col-2">
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <select name="role" id="role" name="role" class="form-select">
                                    <option value="" selected hidden>Select Role</option>
                                    @forelse ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="role">Role</label>
                            </div>
                            <div class="form-floating col">
                                <select name="approver" id="approver" class="form-select">
                                    <option value="" selected hidden>Select Approver</option>
                                    @forelse ($approvers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="approver">Approver</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">Edit a User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUser" method="post">
                        @csrf
                        <div class="d-flex mb-3 gap-2">
                            <input type="hidden" name="userId">
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="first_name" 
                                id="editfirst_name" placeholder="First Name" required>
                                <label for="editfirst_name">First Name</label>
                            </div>
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="last_name" 
                                id="editlast_name" placeholder="Last Name" required>
                                <label for="editlast_name">Last Name</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="email" class="form-control" name="email" 
                                id="editemail" placeholder="Email" required>
                                <label for="editemail">Email</label>
                            </div>
                            <div class="form-floating col">
                                <input type="text" class="form-control" name="contact" 
                                id="editcontact" placeholder="Contact" required>
                                <label for="editcontact">Contact No</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <select name="position" id="editposition" class="form-select">
                                    <option value="" selected hidden>Select Position</option>
                                    @forelse ($positions as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="editposition">Position</label>
                            </div>
                            <div class="form-floating col">
                                <select name="department" id="editdepartment" class="form-select">
                                    <option value="" selected hidden>Select Department</option>
                                    @forelse ($departments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="editdepartment">Department</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <select name="role" id="editrole" class="form-select">
                                    <option value="" selected hidden>Select Role</option>
                                    @forelse ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="editrole">Role</label>
                            </div>
                            <div class="form-floating col">
                                <select name="approver" id="editapprover" class="form-select">
                                    <option value="" selected hidden>Select Approver</option>
                                    @forelse ($approvers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        {{--  --}}
                                    @endforelse
                                </select>
                                <label for="editapprover">Approver</label>
                            </div>
                        </div>
                        <div class="d-flex mb-3 gap-2">
                            <div class="form-floating col">
                                <input type="password" class="form-control" name="oldpassword" 
                                id="oldpassword" placeholder="Old Password">
                                <label for="oldpassword">Old Password</label>
                            </div>
                            <div class="form-floating col">
                                <input type="password" class="form-control" name="newpassword" 
                                id="newpassword" placeholder="New Password">
                                <label for="newpassword">New Password</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary edit-form-btn">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['colvis'],
				scrollX: true,
                ajax: "{{ route('users.data') }}",
                columns: [
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'position_id'},
                    {data: 'department_id', searchable: true},
                    {data: 'role_id'},
                    {data: 'created_at'},
                    {data: 'created_by'},
                    {data: 'actions', orderable: false, searchable: false}
                ]
            });

            $('#addUser').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('user.add') }}",
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

            function displayErrorFields(errorObj, formId) {
                // const formById
                // Object.keys(errorObj).forEach(key => {
                //     console.log(`Field: ${key} | Value: ${errorObj[key]}`);
                // });
                // console.log('form: ', formId);
                
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errorObj, function(field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');

                    input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                });
            }

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                fetchUserData(id);
            });

            function fetchUserData(id) {
                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('id', id);
                formData.append('_token', token);

                $.ajax({
                    url: '{{ route("user.details.get") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const res = JSON.parse(response);

                        if (res.status == 'success') {
                            const form = $('#editUser');
                            form.find('input:text, input:password, input[type=email], textarea, select').val('');
                            const hiddenInput = form.find('input[name="userId"]');
                            const departmentTeam = res.departmentTeam;
                            const approverId = res.data.approver_id;

                            if (departmentTeam) {
                                console.log("departmentTeam: ", departmentTeam);
                                console.log("approverId: ", approverId);
                                addTeamDropdown(departmentTeam, approverId);
                            }

                            const firstName = form.find('input[name="first_name"]');
                            const lastName = form.find('input[name="last_name"]');
                            const email = form.find('input[name="email"]');
                            const contact = form.find('input[name="contact"]');
                            const position = form.find('select[name="position"]');
                            const department = form.find('select[name="department"]');
                            const role = form.find('select[name="role"]');

                            hiddenInput.val(res.data.id);
                            firstName.val(res.data.first_name);
                            lastName.val(res.data.last_name);
                            email.val(res.data.email);
                            contact.val(res.data.contact_no);
                            role.val(res.data.role_id);
                            
                            const positionValue = res.data.position_id?.toString();
                            const departmentValue = res.data.department_id?.toString();
                            
                            if (res.data.position_id && position.find(`option[value="${positionValue}"]`).length) {
                                position.val(positionValue);
                            }
                            
                            if (res.data.department_id && department.find(`option[value="${departmentValue}"]`).length) {
                                department.val(departmentValue);
                            }

                            const modalEl = document.getElementById('editUserModal');
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, error: function (xhr) {
                        console.error("error: ", xhr);
                    }
                });
            }

            $('#editUser').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("user.update") }}',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const res = JSON.parse(response);
                        alertMessage(res.message, res.status);

                    },
                    error: function(xhr) {
                        if (xhr.status === 500) {
                            console.error('error: ', xhr.responseText);
                            alertMessage("Something went wrong!", "error");
                        } else {
                            displayErrorFields(xhr.responseJSON?.errors, e.currentTarget.id);
                        }
                    }
                });
            });

            function addTeamDropdown(teamUsers, id) {
                const selectEl = $('#editapprover');
                selectEl.empty();

                const optDefault = $('<option></option>', {
                    value: "",
                    selected: !id ? true : false,
                    hidden: true,
                    text: "Select Approver",
                });
                selectEl.append(optDefault);

                teamUsers.forEach(user => {
                    const opt = $('<option></option>', {
                        value: user.id,
                        selected: id == user.id ? true : false,
                        text: user.name
                    });
                    selectEl.append(opt);
                });
            }

            $('select[name="department"]').on('change', function(e) {
                const selectEl = e.currentTarget;
                let modalEl = "add";

                if (selectEl.id == "editdepartment") {
                    modalEl = "edit";
                }

                fetchUsersByDepartment(selectEl.value, modalEl);
            });
            
            function fetchUsersByDepartment(id, modal) {
                let url = '{{ route("user.department.get", ":id") }}';
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (res) {
                        const selectEl = (modal == "edit" ? $('#editapprover') : $('#approver'));
                        selectEl.empty();
                        
                        const optDefault = $('<option></option>')
                            .val("")
                            .text("Select Approver")
                            .attr({
                                selected: true,
                                hidden: true
                            });
                        selectEl.append(optDefault);
                        
                        res.forEach(item => {
                            const opt = $('<option></option>')
                                .val(item.id)
                                .text(item.name);
                            selectEl.append(opt);
                        });
                    }, error: function (xhr) {
                        console.error("error: ", xhr);
                    }
                });
            }

            $(document).on('click', '.add-btn, .edit-btn', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });
        });
    </script>
@endpush