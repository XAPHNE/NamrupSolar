@extends('layouts.adminLTE')

@section('title')
    Users
@endsection

@section('page_title')
    Users
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="row">
    <div class="col col-sm-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="d-inline">User List</h3>
                <div class="card-tools">
                    @if (auth()->user()->is_admin)
                        <button id="addUserButton" class="btn btn-default"><i class="fas fa-plus"></i></button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table id="usersTable" class="table display nowrap table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            @if (auth()->user()->is_admin)
                                <th class="text-center">Must Change Password</th>
                                <th class="text-center">Is Admin</th>
                                <th class="text-center">Is Editor</th>
                                <th class="text-center">Is Creator</th>
                                <th class="text-center">Is Approver</th>
                                <th class="text-center">Is Viewer</th>
                                <th class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $user->name }}</td>
                                <td class="text-center">{{ $user->email }}</td>
                                @if (auth()->user()->is_admin)
                                    <td class="text-center">
                                        @if ($user->must_change_password)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_admin)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_creator)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_editor)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_approver)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_viewer)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning edit-button"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-must_change_password="{{ $user->must_change_password }}"
                                            data-is_admin="{{ $user->is_admin }}"
                                            data-is_creator="{{ $user->is_creator }}"
                                            data-is_editor="{{ $user->is_editor }}"
                                            data-is_approver="{{ $user->is_approver }}"
                                            data-is_viewer="{{ $user->is_viewer }}"><i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger delete-button"
                                            data-id="{{ $user->id }}"><i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <!-- Card Footer goes here -->
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addNewUserModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="form-group">
                                <label for="must_change_password">Must Change Password:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="must_change_password" value="0">
                                <input type="checkbox" class="form-control" id="must_change_password" name="must_change_password" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                            <div class="form-group">
                                <label for="is_creator">Is Creator:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="is_creator" value="0">
                                <input type="checkbox" class="form-control role-toggle" id="is_creator" name="is_creator" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                            <div class="form-group">
                                <label for="is_approver">Is Approver:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="is_approver" value="0">
                                <input type="checkbox" class="form-control role-toggle" id="is_approver" name="is_approver" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password:</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                            <div class="form-group">
                                <label for="is_admin">Is Admin:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="is_admin" value="0">
                                <input type="checkbox" class="form-control role-toggle" id="is_admin" name="is_admin" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                            <div class="form-group">
                                <label for="is_editor">Is Editor:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="is_editor" value="0">
                                <input type="checkbox" class="form-control role-toggle" id="is_editor" name="is_editor" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                            <div class="form-group">
                                <label for="is_viewer">Is Viewer:</label>
                                <!-- Add a hidden input with a value of false -->
                                <input type="hidden" name="is_viewer" value="0">
                                <input type="checkbox" class="form-control role-toggle" id="is_viewer" name="is_viewer" value="1" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation user Modal -->
<div class="modal fade" id="deleteConfirmationUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
@endpush

@push('scripts')
<script>
    new DataTable('#usersTable');

    $(document).ready(function () {
        // Handle role toggle buttons to allow only one active at a time
        $('.role-toggle').on('change', function () {
            if ($(this).is(':checked')) {
                $('.role-toggle').not(this).prop('checked', false).change(); // Uncheck others and trigger change to update UI
            }
        });

        // Handle Add Button
        $('#addUserButton').on('click', function () {
            $('#modalTitle').text('Add New User');
            $('#userForm').attr('action', '{{ route('users.store') }}');
            $('#userForm').attr('method', 'POST');
            $('#saveButton').text('Save');
            $('#userForm input[name="_method"]').remove(); // Remove any existing _method field for update
            $('#name').val('');
            $('#email').val('');

            // Reset all toggles and refresh the toggle UI
            $('#must_change_password').prop('checked', false).change();
            $('#is_admin').prop('checked', false).change();
            $('#is_creator').prop('checked', false).change();
            $('#is_editor').prop('checked', false).change();
            $('#is_approver').prop('checked', false).change();
            $('#is_viewer').prop('checked', false).change();

            $('#addNewUserModal').modal('show');
        });

        // Handle Edit Button
        $('.edit-button').on('click', function () {
            var userId = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var must_change_password = parseInt($(this).data('must_change_password')) === 1;
            var is_admin = parseInt($(this).data('is_admin')) === 1;
            var is_creator = parseInt($(this).data('is_creator')) === 1;
            var is_editor = parseInt($(this).data('is_editor')) === 1;
            var is_approver = parseInt($(this).data('is_approver')) === 1;
            var is_viewer = parseInt($(this).data('is_viewer')) === 1;

            $('#modalTitle').text('Update User');
            $('#userForm').attr('action', '/users/' + userId);
            $('#userForm').append('<input type="hidden" name="_method" value="PATCH">');
            $('#saveButton').text('Update');
            $('#name').val(name);
            $('#email').val(email);

            // Set the correct state for each toggle and refresh the UI
            $('#must_change_password').prop('checked', must_change_password).change();
            $('#is_admin').prop('checked', is_admin).change();
            $('#is_creator').prop('checked', is_creator).change();
            $('#is_editor').prop('checked', is_editor).change();
            $('#is_approver').prop('checked', is_approver).change();
            $('#is_viewer').prop('checked', is_viewer).change();

            $('#addNewUserModal').modal('show');
        });

        // Handle Delete Button
        $('.delete-button').on('click', function () {
            var userId = $(this).data('id'); // Get the id of the user to be deleted
            var deleteUrl = '/users/' + userId; // Construct the delete route URL
            $('#deleteForm').attr('action', deleteUrl); // Set the action attribute to the correct URL
            $('#deleteConfirmationUserModal').modal('show'); // Show the modal
        });

        // Reset toggle state when the modal is closed
        $('#addNewUserModal').on('hidden.bs.modal', function () {
            // Reset the form fields and toggle states
            $('#userForm')[0].reset();
            // Reset the toggle state and refresh UI
            $('.role-toggle').prop('checked', false).change(); // Ensure UI is updated
            $('#must_change_password').prop('checked', false).change(); // Ensure UI is updated
        });
    });
</script>
<script>
    // Check if there's a success message in the session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    @endif

    // Check if there's an error message in the session
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    @endif

    // Show validation errors in SweetAlert
    @if ($errors->any())
        var errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Validation Errors',
            text: errorMessages,
        });
    @endif
</script>
@endpush
