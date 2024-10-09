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
                    <button id="addUserButton" class="btn btn-default"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <table id="usersTable" class="display nowrap table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Is Admin</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $user->name }}</td>
                                <td class="text-center">{{ $user->email }}</td>
                                <td class="text-center">
                                    @if ($user->isAdmin)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-danger">No</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning edit-button" 
                                        data-id="{{ $user->id }}" 
                                        data-name="{{ $user->name }}" 
                                        data-email="{{ $user->email }}"><i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger delete-button" 
                                        data-id="{{ $user->id }}"><i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
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

@endsection

@push('styles')
    {{-- Add Styles Here --}}
@endpush

@push('scripts')
<script>
    new DataTable('#usersTable');

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
</script>
@endpush