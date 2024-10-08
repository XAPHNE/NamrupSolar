@php
    use Carbon\Carbon;
@endphp
@extends('layouts.adminLTE')

@section('title')
    Supply
@endsection

@section('page_title')
    Supply
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Supply</li>
@endsection

@section('content')
<div class="row">
    <div class="col col-sm-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="d-inline">Supplies</h3>
                <div class="card-tools">
                    <button id="addSupplyButton" class="btn btn-default"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <table id="suppliesTable" class="display nowrap table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Particulars</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ordered on</th>
                            <th class="text-center">Delivered on</th>
                            <th class="text-center">Action Taken By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplies as $supply)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $supply->particulars }}</td>
                                <td class="text-center">{{ $supply->status }}</td>
                                <td class="text-center">{{ Carbon::parse($supply->ordered_on)->format('d-m-Y, h:i A') }}</td>
                                <td class="text-center">{{ $supply->delivered_on ? Carbon::parse($supply->delivered_on)->format('d-m-Y, h:i A') : 'N/A' }}</td>
                                <td class="text-center">{{ $supply->actionTaker->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning" 
                                        data-id="{{ $supply->id }}" 
                                        data-particulars="{{ $supply->particulars }}" 
                                        data-status="{{ $supply->status }}" 
                                        data-ordered_on="{{ $supply->ordered_on }}" 
                                        data-delivered_on="{{ $supply->delivered_on }}"><i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger delete-button" 
                                        data-id="{{ $supply->id }}"><i class="fas fa-trash-alt"></i>
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

<!-- Add/Edit Supply Modal -->
<div class="modal fade" id="addNewSupplyModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Supply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="supplyForm" method="POST">
                @csrf
                <!-- For Edit, we dynamically add _method="PATCH" using JavaScript -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="particulars">Particulars:</label>
                        <input type="text" class="form-control" id="particulars" name="particulars" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Ordered">Ordered</option>
                            <option value="In-Transit">In-Transit</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ordered_on">Ordered on:</label>
                        <input type="datetime-local" class="form-control" id="ordered_on" name="ordered_on" required/>
                    </div>
                    <div class="form-group">
                        <label for="delivered_on">Delivered on:</label>
                        <input type="datetime-local" class="form-control" id="delivered_on" name="delivered_on"/>
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

<!-- Delete Confirmation Supply Modal -->
<div class="modal fade" id="deleteConfirmationSupplyModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this supply?</p>
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
    {{-- Add Styles Here --}}
@endpush

@push('scripts')
<script>
    new DataTable('#suppliesTable');

    $(document).ready(function () {
        // Handle Add Button
        $('#addSupplyButton').on('click', function () {
            $('#modalTitle').text('Add New Supply');
            $('#supplyForm').attr('action', '{{ route('supplies.store') }}');
            $('#supplyForm').attr('method', 'POST');
            $('#saveButton').text('Save');
            $('#supplyForm input[name="_method"]').remove(); // Remove any existing _method field for update
            $('#particulars').val('');
            $('#status').val('Ordered');
            $('#ordered_on').val('');
            $('#delivered_on').val('');
            $('#addNewSupplyModal').modal('show');
        });

        // Handle Edit Button
        $('.btn-warning').on('click', function () {
            var supplyId = $(this).data('id');
            var particulars = $(this).data('particulars');
            var status = $(this).data('status');
            var ordered_on = $(this).data('ordered_on');
            var delivered_on = $(this).data('delivered_on');

            // Format the datetime values for ordered_on and delivered_on to 'Y-m-d\TH:i'
            var formattedOrderedOn = new Date(ordered_on).toISOString().slice(0, 16);
            var formattedDeliveredOn = delivered_on ? new Date(delivered_on).toISOString().slice(0, 16) : '';

            $('#modalTitle').text('Update Supply');
            $('#supplyForm').attr('action', '/supplies/' + supplyId);
            $('#supplyForm').append('<input type="hidden" name="_method" value="PATCH">');
            $('#saveButton').text('Update');
            $('#particulars').val(particulars);
            $('#status').val(status);
            $('#ordered_on').val(formattedOrderedOn);
            $('#delivered_on').val(formattedDeliveredOn);
            $('#addNewSupplyModal').modal('show');
        });

        // Handle Delete Button
        $('.delete-button').on('click', function () {
            var supplyId = $(this).data('id'); // Get the id of the supply to be deleted
            var deleteUrl = '/supplies/' + supplyId; // Construct the delete route URL
            $('#deleteForm').attr('action', deleteUrl); // Set the action attribute to the correct URL
            $('#deleteConfirmationSupplyModal').modal('show'); // Show the modal
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
</script>
@endpush
