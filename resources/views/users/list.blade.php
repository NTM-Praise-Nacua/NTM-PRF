@extends('layouts.app')

@section('content')
    <x-container pageTitle="User List">
        <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add</button>

        <div class="">
            <table id="users-table" class="table table-hover">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Position</td>
                        <td>Department</td>
                        <td>Date Registered</td>
                        <td>Created by</td>
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

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Add a User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.add') }}" method="post">
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
                            <div class="col-2"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
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
                ajax: "{{ route('users.data') }}",
                columns: [
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'position_id'},
                    {data: 'department_id'},
                    {data: 'created_at'},
                    {data: 'created_by'},
                    {data: 'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endpush