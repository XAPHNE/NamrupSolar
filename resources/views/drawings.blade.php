@extends('layouts.adminLTE')

@section('title', 'Drawings')

@section('page_title', 'Drawings')

@section('breadcrumb')
    <li class="breadcrumb-item active">Drawings</li>
@stop

@section('content')
    <div class="row">
        @foreach($drawings as $drawing)
            <div class="col-sm-2 mb-2">
                <div class="card shadow-sm bg-info clickable-card" 
                    data-id="{{ $drawing->id }}" 
                    data-name="{{ $drawing->name }}" 
                    style="height: 60px; margin: 0 auto;">
                    <div class="p-3 text-center">
                        <p class="text-center mb-0" style="font-size: 14px;">{{ $drawing->name }}</p>
                        <a href="#" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @endforeach    
    </div>

    <!-- Charts and DataTable -->
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Line Chart</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable for Drawing Details -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="d-inline">Drawing Details</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#addDrawingModal" id="addNewDrawing"><i class="fas fa-plus"></i> Add New Drawing</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="drawingDetailsTable" class="display nowrap table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Scope</th>
                                <th>Submitted At</th>
                                {{-- <th>Submitted By</th> --}}
                                {{-- <th>Latest Comment</th> --}}
                                {{-- <th>Commented At</th> --}}
                                {{-- <th>Commented By</th> --}}
                                {{-- <th>Resubmitted At</th> --}}
                                <th>Approved At</th>
                                {{-- <th>Approved By</th> --}}
                                <th>Drawing File</th>
                                {{-- <th>Supporting Documents</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Update Drawing Modal -->
    <div class="modal fade" id="drawingModal" tabindex="-1" role="dialog" aria-labelledby="drawingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="drawingModalLabel">Add New Drawing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="drawingForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="drawingId" name="drawingId">
                        <div class="form-group">
                            <label for="drawing_id">Drawing Category</label>
                            <select class="form-control" id="drawing_id" name="drawing_id" required>
                                <option value="">Select Category</option>
                                @foreach($drawings as $drawing)
                                    <option value="{{ $drawing->id }}">{{ $drawing->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="drawing_details_no">Drawing Number</label>
                            <input type="text" class="form-control" id="drawing_details_no" name="drawing_details_no" required>
                        </div>
                        <div class="form-group">
                            <label for="drawing_details_name">Drawing Name</label>
                            <input type="text" class="form-control" id="drawing_details_name" name="drawing_details_name" required>
                        </div>
                        <div class="form-group">
                            <label for="isScopeDrawing">Is Scope</label>
                            <input type="checkbox" id="isScopeDrawing" name="isScopeDrawing" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" value="1" data-toggle="toggle">
                        </div>
                        <div class="form-group">
                            <label for="submitted_at">Submitted At</label>
                            <input type="datetime-local" class="form-control" id="submitted_at" name="submitted_at" required>
                        </div>
                        <div class="form-group">
                            <label for="drawing_file">Upload Drawing</label>
                            <input type="file" class="form-control-file" id="drawing_file" name="drawing_file">
                        </div>
                        <div class="form-group">
                            <label for="report_file">Upload Supporting Documents (Optional)</label>
                            <input type="file" class="form-control-file" id="report_file" name="report_file">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this drawing?
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $(document).ready(function () {
        // Initialize DataTable with scrollX enabled
        var table = $('#drawingDetailsTable').DataTable({
            scrollX: true,  // Enable horizontal scrolling
            columnDefs: [
                { targets: 0, visible: false }  // Hide the first column (ID)
            ]
        });

        // Add click event listener to rows
        $('#drawingDetailsTable tbody').on('click', 'tr', function () {
            // Get the data for the clicked row
            var data = table.row(this).data();

            // Assuming the ID is in the first column (index 0)
            var drawingId = data[0]; // Or whichever column contains the drawing ID

            // Redirect to the edit page
            window.location.href = `/drawings/${drawingId}/edit`;
        });

        // Initialize global line chart data from the controller
        var lineChartData = @json($lineChartData);

        // Initialize the Line Chart with global data
        const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Scope Drawings', 'Submitted Drawings', 'Approved Drawings', 'Total Drawings'],
                datasets: [{
                    label: 'Global Drawing Stats',
                    data: [
                        lineChartData.totalScopeDrawings,
                        lineChartData.totalSubmittedDrawings,
                        lineChartData.totalApprovedDrawings,
                        lineChartData.totalDrawings
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                }]
            }
        });

        // Initialize the Bar Chart for drawing-specific data
        let barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Scope Drawings', 'Submitted Drawings', 'Approved Drawings'],
                datasets: [{
                    label: 'Drawing Stats By Drawing Type',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    data: [0, 0, 0], // Empty data, will be updated on click
                }]
            }
        });

        // Handle card click event for fetching drawing details and updating bar chart
        $('.clickable-card').on('click', function() {
            const drawingId = $(this).data('id');

            // Fetch relevant drawing details and chart data
            fetchDrawingData(drawingId);
        });

        function fetchDrawingData(drawingId) {
            $.ajax({
                url: `/drawings/${drawingId}`, // Using the resource route (show method)
                type: 'GET',
                success: function (response) {
                    // Clear the existing table data
                    table.clear().draw();

                    // Populate the DataTable using the map method with latest data
                    let mappedData = response.details.map(detail => {
                        let drawingFileLink = detail.drawing_file_url ? `<a href="${detail.drawing_file_url}" target="_blank">View Drawing</a>` : '';
                        let reportFileLink = detail.report_file_url ? `<a href="${detail.report_file_url}" target="_blank">View Report</a>` : '';

                        return [
                            detail.id,
                            detail.drawing_details_no,
                            detail.drawing_details_name,
                            detail.isScopeDrawing,
                            detail.submitted_at,
                            // detail.submitted_by,
                            // detail.comment_body,  // Latest comment body
                            // detail.commented_at,  // Latest commented_at
                            // detail.commented_by,  // Latest commenter
                            // detail.resubmitted_at,  // Latest resubmitted_at
                            detail.approved_at,
                            // detail.approved_by,
                            `<a href="${detail.drawing_file_url}" target="_blank">View Drawing</a>`,
                            // `<a href="${detail.report_file_url}">View Supporting Document</a>`,
                            // drawingFileLink,  // Latest drawing file link
                            // reportFileLink,  // Latest report file link
                            `<button class="btn btn-danger delete-drawing" data-id="${detail.id}">Delete</button>`
                        ];
                    });

                    // Add rows to the table
                    table.rows.add(mappedData).draw();

                    // Update Bar Chart with all data for the drawing
                    barChart.data.datasets[0].data = [
                        response.chartData.scopeDrawings,
                        response.chartData.submittedDrawings,
                        response.chartData.approvedDrawings
                    ];
                    barChart.update();
                },
                error: function (error) {
                    console.log("Error fetching drawing data:", error);
                }
            });
        }

        // Open Add Drawing Modal
        $('#addNewDrawing').on('click', function() {
            $('#drawingForm')[0].reset();
            $('#drawingModalLabel').text('Add New Drawing');
            $('#drawingModal').modal('show');
        });

        // Save Drawing (Add/Update)
        $('#drawingForm').on('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let drawingId = $('#drawingId').val();

            $.ajax({
                url: drawingId ? `/drawings/${drawingId}` : `/drawings`,
                method: drawingId ? 'PUT' : 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#drawingModal').modal('hide');
                    fetchDrawingData(drawingId ? drawingId : response.id);  // Reload data via fetchDrawingData
                    alert('Drawing saved successfully!');
                },
                error: function(xhr) {
                    console.error('Error saving drawing:', xhr);
                }
            });
        });

        // Delete Drawing
        let deleteDrawingId = null;
        $(document).on('click', '.delete-drawing', function() {
            deleteDrawingId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: `/drawings/${deleteDrawingId}`,
                method: 'DELETE',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    fetchDrawingData(deleteDrawingId);  // Reload data after deletion
                    alert('Drawing deleted successfully!');
                },
                error: function(xhr) {
                    console.error('Error deleting drawing:', xhr);
                }
            });
        });
    });
</script>
@endpush
