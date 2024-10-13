@php
    use Carbon\Carbon;
@endphp
@extends('layouts.adminLTE')

@section('title')
    Update {{ $drawingDetail->drawing_details_name }} Details
@endsection

@section('page_title')
    Update {{ $drawingDetail->drawing_details_name }}
    {{-- {{ $drawingDetail->comments->drawing_details_id }} --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/drawings') }}">Drawings</a></li>
    <li class="breadcrumb-item active">Update {{ $drawingDetail->drawing_details_name }}</li>
@stop

@section('content')
<div class="row">
    <div class="col-sm-7">
        <div class="card card-info">
            <div class="card-header">
                <h5 class="card-title">Uploaded Drawings</h5>
                <div class="card-tools">
                    @if (auth()->user()->is_admin || auth()->user()->is_creator)
                        <button title="Upload Updated Drawing" type="button" class="btn btn-default" data-toggle="modal" data-target="#addUpdatedDrawingModal" id="addUpdatedDrawing" data-drawing-detail-id="{{ $drawingDetail->id }}"><i class="fas fa-plus"></i></button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table id="uploadedDrawingsTable" class="table display compact nowrap table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr class="table-primary">
                            {{-- <th>Number</th> --}}
                            <th class="text-center">{{ $drawingDetail->drawing_details_no }}</th>
                            <th class="text-center">Comment</th>
                            <th class="text-center">Commented at</th>
                            @if (auth()->user()->is_admin || auth()->user()->is_approver)
                                <th class="text-center nosort">Add Comment</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="justify-center">
                        @foreach($drawingDetail->drawingFiles as $file)
                            <tr>
                                {{-- <td>{{ $drawingDetail->drawing_details_no }}</td> --}}
                                <td title="View/Download Drawing" class="text-center"><a href="{{ asset($file->file_path) }}" target="_blank"><i class="far fa-file-pdf fa-lg" style="color: #ff0000;"></i></a></td>
                                <td class="text-center">
                                    @foreach($file->comments as $comment)
                                        {{ $comment->comment_body }}<br>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @foreach($file->comments as $comment)
                                        {{ Carbon::parse($comment->commented_at)->format('d-m-Y, h:i A') }}<br>
                                    @endforeach
                                </td>
                                @if (auth()->user()->is_admin || auth()->user()->is_approver)
                                    <td class="text-center">
                                        <button title="Add Comment" class="btn btn-success addNewComment" data-file-id="{{ $file->id }}" @if($drawingDetail->approved_at) disabled @endif><i class="fas fa-plus"></i></button>
                                        {{-- <button class="btn btn-warning"><i class="fas fa-edit"></i></button> --}}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
                @if (auth()->user()->is_admin || auth()->user()->is_approver)
                    <form action="{{ route('drawings.update', $drawing->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="approve_drawing">
                        <div class="form-group">
                            <button type="submit" id="approveDrawing" data-drawing-id="{{ $drawing->id }}"
                                class="btn btn-info"
                                @if($drawingDetail->approved_at) disabled @endif>
                                {{ $drawingDetail->approved_at ? 'Approved' : 'Approve Drawing' }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="card card-info">
            <div class="card-header">
                <h5 class="card-title">Supporting Documents</h5>
                <div class="card-tools">
                    @if (auth()->user()->is_admin || auth()->user()->is_creator)
                        <button title="Upload Supporting Documents" type="button" class="btn btn-default" data-toggle="modal" data-target="#addUpdatedDrawingModal" id="addSupportingDocument" data-drawing-detail-id="{{ $drawingDetail->id }}"><i class="fas fa-plus"></i></button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table id="uploadedReportsTable" class="table display compact nowrap table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr class="table-primary">
                            {{-- <th>Number</th> --}}
                            <th class="text-center">{{ $drawingDetail->drawing_details_no }}</th>
                            <th class="text-center">File Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drawingDetail->reportFiles as $file)
                            <tr>
                                {{-- <td>{{ $drawingDetail->drawing_details_no }}</td> --}}
                                <td title="View/Download Supporting Document" class="text-center"><a href="{{ asset($file->file_path) }}" target="_blank"><i class="far fa-file-pdf fa-lg" style="color: #ff0000;"></i></a></td>
                                <td class="text-center">{{ preg_replace('/^\d+_/', '', basename($file->file_path)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Upload Updated Drawing Modal -->
<div class="modal fade" id="addUpdatedDrawingModal" tabindex="-1" role="dialog" aria-labelledby="addUpdatedDrawingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdatedDrawingModalLabel">Add Updated Drawing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addUpdatedDrawingForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="drawingDetailId" id="drawingDetailId">
                    <div class="form-group">
                        <label id="fileLabel" for="drawing_file">Upload Updated Drawing:</label>
                        <input type="file" class="form-control" id="drawing_file" name="drawing_file">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveDrawingBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add New Comment Modal -->
<div class="modal fade" id="addNewCommentModal" tabindex="-1" role="dialog" aria-labelledby="addNewCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewCommentModalLabel">Add New Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('comments.store') }}" id="addNewCommentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="drawing_file_id" id="drawing_file_id">
                    <div class="form-group">
                        <label for="comment">Add New Comment:</label>
                        <textarea class="form-control" rows="3" name="comment_body" id="comment"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    new DataTable('#uploadedDrawingsTable', {
        searching: false,
        lengthChange: false,
        paging: false,
        scrollX: true,
        columnDefs: [
            {
                targets: 'nosort',
                orderable: false,
            },
        ]
    });
    new DataTable('#uploadedReportsTable', {
        searching: false,
        lengthChange: false,
        paging: false,
        scrollX: true,
    });
</script>
<script>
    $(document).ready(function () {
        //  Open Modal
        $('#addUpdatedDrawing').on('click', function() {
            var drawingDetailID = $(this).data('drawing-detail-id');
            $('#addUpdatedDrawingForm')[0].reset();
            $('#addUpdatedDrawingModalLabel').text('Add Updated Drawing');
            $('#fileLabel').text('Upload Updated Drawing:');
            $('input[name="drawingDetailId"]').val(drawingDetailID);
            $('#addUpdatedDrawingForm').attr('action', "{{ route('drawing-files.store') }}");
            $('#addUpdatedDrawingModal').modal('show');
        })

        $('#addSupportingDocument').on('click', function() {
            var drawingDetailID = $(this).data('drawing-detail-id');
            $('#addUpdatedDrawingForm')[0].reset();
            $('#addUpdatedDrawingModalLabel').text('Add Supporting Documents');
            $('#fileLabel').text('Upload Supporting Document:');
            $('input[name="drawingDetailId"]').val(drawingDetailID);
            // $('input[name="fileType"]').val('supporting_document');
            $('#addUpdatedDrawingForm').attr('action', "{{ route('report-files.store') }}");
            $('#addUpdatedDrawingModal').modal('show');
        })

        $('.addNewComment').on('click', function() {
            var drawingFileId = $(this).data('file-id');    // Get the drawing file ID from the data attribute
            $('#addNewCommentForm')[0].reset();
            $('#addNewCommentModalLabel').text('Add New Comment');
            $('input[name="drawing_file_id"]').val(drawingFileId);  // Set the drawing_file_id
            $('#addNewCommentModal').modal('show');
        })

        $('#approveDrawing').on('click', function() {
            var drawingId = $($this).data('drawing-id');
        })
    })
</script>
@endpush
