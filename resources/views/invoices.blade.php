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
                <h3 class="card-title">Invoices</h3>
                <div class="card-tools">
                    <button id="addInvoiceButton" class="btn btn-default"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <table id="invoicesTable" class="display nowrap table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center">Bill No</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Raised at</th>
                            <th class="text-center">File</th>
                            <th class="text-center">Paid at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <th class="text-center">{{ $invoice->bill_no }}</th>
                                <th class="text-center">{{ $invoice->description }}</th>
                                <th class="text-center">{{ $invoice->amount }}</th>
                                <th class="text-center">{{ Carbon::parse($invoice->raised_at)->format('d-m-Y, h:i A') }}</th>
                                <th class="text-center"><a href="{{ $invoice->file_path }}" target="_blank"><i title="View/Download" class="fas fa-eye"></i></a></th>
                                <th class="text-center">{{ $invoice->paid_at ? Carbon::parse($invoice->paid_at)->format('d-m-Y, h:i A') : 'N/A'  }}</th>
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
<!-- Add New Invoice Modal -->
<div class="modal fade" id="addNewInvoiceModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="billNumberLabel" for="billNumber">Bill No:</label>
                            <input type="text" class="form-control" id="billNumber" name="bill_no">
                        </div>
                        <div class="form-group">
                            <label id="descriptionLabel" for="description">Description:</label>
                            <textarea type="text" class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label id="amountLabel" for="amount">Amount:</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="amount" name="amount"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="invoiceRaisedAtLabel" for="invoiceRaisedAt">Raised at:</label>
                            <input type="datetime-local" class="form-control" id="invoiceRaisedAt" name="raised_at"/>
                        </div>
                        <div class="form-group">
                            <label id="invoiceFileLabel" for="invoiceFile">Upload Invoice:</label>
                            <input type="file" class="form-control" id="invoiceFile" name="file_path"/>
                        </div>
                        &nbsp;
                        <div class="form-group">
                            <label id="invoicePaidAtLabel" for="invoicePaidAt">Paid at:</label>
                            <input type="datetime-local" class="form-control" id="invoicePaidAt" name="paid_at"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new DataTable('#invoicesTable');
</script>
<script>
    $(document).ready(function () {
        $('#addInvoiceButton').on('click', function () {
            $('#addNewInvoiceModal').modal('show');
        })
    })
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