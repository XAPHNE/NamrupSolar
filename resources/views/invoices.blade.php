@php
    use Carbon\Carbon;
@endphp
@extends('layouts.adminLTE')

@section('title')
    Invoices
@endsection

@section('page_title')
    Invoices
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Invoices</li>
@endsection

@section('content')
<div class="row">
    <div class="col col-sm-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="d-inline">Invoices</h3>
                <div class="card-tools">
                    @if (auth()->user()->is_admin || auth()->user()->is_creator)
                        <button id="addInvoiceButton" class="btn btn-default"><i class="fas fa-plus"></i></button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table id="invoicesTable" class="table display nowrap table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">#</th>
                            <th class="text-center">Bill No</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Raised at</th>
                            <th class="text-center nosort">File</th>
                            <th class="text-center">Paid at</th>
                            @if (auth()->user()->is_admin)
                                <th class="text-center nosort">Action Taken By</th>
                            @endif
                            @if (auth()->user()->is_admin || auth()->user()->is_editor)
                                <th class="text-center nosort">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $invoice->bill_no }}</td>
                                <td class="text-center">{{ $invoice->description }}</td>
                                <td class="text-center">{{ $invoice->amount }}</td>
                                <td class="text-center">{{ Carbon::parse($invoice->raised_at)->format('d-m-Y, h:i A') }}</td>
                                <td class="text-center"><a href="{{ $invoice->file_path }}" target="_blank"><i title="View/Download" class="fas fa-eye"></i></a></td>
                                <td class="text-center">{{ $invoice->paid_at ? Carbon::parse($invoice->paid_at)->format('d-m-Y, h:i A') : 'N/A' }}</td>
                                @if (auth()->user()->is_admin)
                                    <td class="text-center">{{ $invoice->actionTaker->name ?? 'N/A' }}</td>
                                @endif
                                @if (auth()->user()->is_admin || auth()->user()->is_editor)
                                    <td class="text-center">
                                        <button class="btn btn-warning edit-button"
                                            data-id="{{ $invoice->id }}"
                                            data-bill_no="{{ $invoice->bill_no }}"
                                            data-description="{{ $invoice->description }}"
                                            data-amount="{{ $invoice->amount }}"
                                            data-raised_at="{{ $invoice->raised_at }}"
                                            data-paid_at="{{ $invoice->paid_at }}"><i class="fas fa-edit"></i>
                                        </button>
                                        @if (auth()->user()->is_admin)
                                            <button class="btn btn-danger delete-button"
                                                data-id="{{ $invoice->id }}"><i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
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

<!-- Add/Edit Invoice Modal -->
<div class="modal fade" id="addNewInvoiceModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="invoiceForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="billNumber">Bill No:</label>
                            <input type="text" class="form-control" id="billNumber" name="bill_no">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea type="text" class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="amount" name="amount"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="invoiceRaisedAt">Raised at:</label>
                            <input type="datetime-local" class="form-control" id="invoiceRaisedAt" name="raised_at"/>
                        </div>
                        <div class="form-group">
                            <label for="invoiceFile">Upload Invoice:</label>
                            <input type="file" class="form-control" id="invoiceFile" name="file_path"/>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="invoicePaidAt">Paid at:</label>
                            <input type="datetime-local" class="form-control" id="invoicePaidAt" name="paid_at"/>
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

<!-- Delete Confirmation Invoice Modal -->
<div class="modal fade" id="deleteConfirmationInvoiceModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this invoice?</p>
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

@push('scripts')
<script>
$(document).ready(function () {
    new DataTable('#invoicesTable', {
        columnDefs: [
            {
                targets: 'nosort',
                orderable: false,
            }
        ],
    });
    // Handle Add Button
    $('#addInvoiceButton').on('click', function () {
        $('#modalTitle').text('Add New Invoice');
        $('#invoiceForm').attr('action', '{{ route('invoices.store') }}');
        $('#invoiceForm').attr('method', 'POST');
        $('#saveButton').text('Save');
        $('#invoiceForm input[name="_method"]').remove(); // Remove any existing _method field for update
        $('#billNumber').val('');
        $('#description').val('');
        $('#amount').val('');
        $('#invoiceRaisedAt').val('');
        $('#invoicePaidAt').val('');
        $('#addNewInvoiceModal').modal('show');
    });

    // Handle Edit Button
    $('.edit-button').on('click', function () {
        var invoiceId = $(this).data('id');
        var billNo = $(this).data('bill_no');
        var description = $(this).data('description');
        var amount = $(this).data('amount');
        var raisedAt = $(this).data('raised_at');
        var paidAt = $(this).data('paid_at');

        // Format the datetime values to 'Y-m-d\TH:i'
        var formattedRaisedAt = new Date(raisedAt).toISOString().slice(0, 16);
        var formattedPaidAt = paidAt ? new Date(paidAt).toISOString().slice(0, 16) : '';

        $('#modalTitle').text('Update Invoice');
        $('#invoiceForm').attr('action', '/invoices/' + invoiceId);
        $('#invoiceForm').append('<input type="hidden" name="_method" value="PATCH">');
        $('#saveButton').text('Update');
        $('#billNumber').val(billNo);
        $('#description').val(description);
        $('#amount').val(amount);
        $('#invoiceRaisedAt').val(formattedRaisedAt);
        $('#invoicePaidAt').val(formattedPaidAt);
        $('#addNewInvoiceModal').modal('show');
    });

    // Handle Delete Button
    $('.delete-button').on('click', function () {
        var invoiceId = $(this).data('id'); // Get the id of the invoice to be deleted
        var deleteUrl = '/invoices/' + invoiceId; // Construct the delete route URL
        $('#deleteForm').attr('action', deleteUrl); // Set the action attribute to the correct URL
        $('#deleteConfirmationInvoiceModal').modal('show'); // Show the modal
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
