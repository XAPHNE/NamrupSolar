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
                    data-total-drawings="{{ $drawing->total_drawings }}" 
                    data-total-scope="{{ $drawing->total_drawings_scope }}" 
                    data-total-submitted="{{ $drawing->total_submitted_drawings }}" 
                    data-total-approved="{{ $drawing->total_approved_drawings }}"
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
            <!-- LINE CHART -->
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
            <!-- BAR CHART -->
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
                    <table id="drawingDetailsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Scope</th>
                                <th style="min-width: 154px">Submitted At</th>
                                <th>Submitted By</th>
                                <th>Comment</th>
                                <th style="min-width: 158px">Commented At</th>
                                <th>Commented By</th>
                                <th style="min-width: 158px">Resubmitted At</th>
                                <th style="min-width: 154px">Approved At</th>
                                <th>Approved By</th>
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

    <!-- Add Drawing Modal -->
    <div class="modal fade" id="addDrawingModal" tabindex="-1" role="dialog" aria-labelledby="addDrawingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDrawingModalLabel">Add New Drawing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addDrawingForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="drawing_id">Drawing Category</label>
                            <select class="form-control" id="drawing_id" name="drawing_id" required>
                                <option value="">Select</option>
                                @foreach ($drawings as $drawing)
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
                            <input type="checkbox" id="isScopeDrawing" name="isScopeDrawing" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary">
                        </div>
                        <div class="form-group">
                            <label for="submitted_at">Submitted At</label>
                            <input type="date" class="form-control" id="submitted_at" name="submitted_at" required>
                        </div>
                        <div class="form-group">
                            <label for="drawing_file">Upload Drawing</label>
                            <input type="file" class="form-control-file" id="drawing_file" name="drawing_file" required>
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
    $(function () {
        // Initialize Bootstrap Toggle on modal show
        $('#addDrawingModal').on('shown.bs.modal', function () {
            $('#isScopeDrawing').bootstrapToggle();
        });

        $('#addNewDrawing').click(function() {
            $('#addDrawingModalLabel').text('Add New Ticket');
            $('#addDrawingForm').trigger('reset');
            $('#addDrawingModal').modal('show');
        });

        // Handle form submission
        $('#addDrawingForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var drawingName = $('#drawing_id option:selected').text();
            var fileName = formData.get('drawing_file').name;
            var filePath = `drawings/${drawingName}/${fileName}`;

            // Append the filepath to the form data
            formData.append('filepath', filePath);

            $.ajax({
                url: '/drawings',  // Ensure this is routed to the correct controller method
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#addDrawingModal').modal('hide');
                    dataTable.ajax.reload();  // Reload the DataTable to show the new drawing detail
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);  // Handle errors appropriately
                }
            });
        });

        var lineChartCanvas = $('#lineChart').get(0).getContext('2d');
        var barChartCanvas = $('#barChart').get(0).getContext('2d');
        var lineChart, barChart;
        var dataTable;

        // Initialize DataTable
        dataTable = $('#drawingDetailsTable').DataTable({
            processing: true,
            serverSide: false,
            paging: true,
            searching: true,
            lengthChange: true,
            autoWidth: false,
            scrollX: true,
            columns: [
                { data: 'drawing_details_no', title: 'Number' },
                { data: 'drawing_details_name', title: 'Name' },
                { data: 'isScopeDrawing', title: 'Scope', render: function(data, type, row) {
                        return data ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                    } 
                },
                { data: 'submitted_at', title: 'Submitted At', render: function(data) {
                        return data ? data : 'N/A';
                    } 
                },
                { data: 'submitted_by', title: 'Submitted By', render: function(data) {  // Display the submitter's name
                        return data ? data : 'N/A';
                    } 
                },
                { data: 'comment_body', title: 'Comment', render: function(data) { // New comment column
                        return data ? data.replace(/, /g, ',<br>') : 'N/A';
                    }
                },
                { data: 'commented_at', title: 'Commented At', render: function(data) {
                        return data ? data.replace(/, /g, ',<br>') : 'N/A';
                    } 
                },
                { data: 'commented_by', title: 'Commented By', render: function(data) {  // Display the commenter's name
                        return data ? data.replace(/, /g, ',<br>') : 'N/A';
                    } 
                },
                { data: 'resubmitted_at', title: 'Resubmitted At', render: function(data) {
                        return data ? data.replace(/, /g, ',<br>') : 'N/A';
                    } 
                },
                { data: 'approved_at', title: 'Approved At', render: function(data) {
                        return data ? data : 'N/A';
                    } 
                },
                { data: 'approved_by', title: 'Approved By', render: function(data) {    // Display the approver's name
                        return data ? data : 'N/A';
                    } 
                },
            ],
            data: []  // Initialize with empty data
        });

        // Handle card clicks
        $('.clickable-card').on('click', function () {
            var drawingId = $(this).data('id');
            var drawingName = $(this).data('name');
            var totalDrawings = $(this).data('total-drawings');
            var totalScope = $(this).data('total-scope');
            var totalSubmitted = $(this).data('total-submitted');
            var totalApproved = $(this).data('total-approved');

            // Fetch data for the charts and DataTable
            $.ajax({
                url: '/drawings/' + drawingId,
                method: 'GET',
                success: function(response) {
                    // Ensure response.details exists and contains the expected data structure
                    if (response.details && response.details.length) {
                        // Update the DataTable with new data
                        dataTable.clear().rows.add(response.details).draw();

                        // Prepare the line chart data
                        var lineChartData = {
                            labels  : ['Total Drawings', 'Scope', 'Submitted', 'Approved'],
                            datasets: [
                                {
                                    label               : drawingName,
                                    backgroundColor     : 'rgba(60,141,188,0.9)',
                                    borderColor         : 'rgba(60,141,188,0.8)',
                                    pointRadius         : false,
                                    pointColor          : '#3b8bba',
                                    pointStrokeColor    : 'rgba(60,141,188,1)',
                                    pointHighlightFill  : '#fff',
                                    pointHighlightStroke: 'rgba(60,141,188,1)',
                                    fill                : false,
                                    data                : [
                                        totalDrawings, 
                                        response.scopeCount, 
                                        response.submittedCount, 
                                        response.approvedCount
                                    ]
                                }
                            ]
                        };

                        // Prepare the bar chart data
                        var barChartData = {
                            labels: ['Scope Drawings', 'Submitted Drawings', 'Approved Drawings'],
                            datasets: [
                                {
                                    label               : 'Count',
                                    backgroundColor     : ['rgba(60,141,188,0.9)', 'rgba(210, 214, 222, 1)', 'rgba(0, 166, 90, 0.9)'],
                                    borderColor         : ['rgba(60,141,188,0.8)', 'rgba(210, 214, 222, 0.8)', 'rgba(0, 166, 90, 0.8)'],
                                    data                : [response.scopeCount, response.submittedCount, response.approvedCount]
                                }
                            ]
                        };

                        // Update the charts with the new data
                        updateLineChart(lineChartData);
                        updateBarChart(barChartData);
                    } else {
                        // Handle case where there is no data
                        dataTable.clear().draw();  // Clear the table if no details are returned
                        updateLineChart({ datasets: [] });  // Clear the line chart
                        updateBarChart({ datasets: [] });  // Clear the bar chart
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);  // Log any errors for debugging
                }
            });
        });

        function updateLineChart(data) {
            if (lineChart) {
                lineChart.destroy();
            }

            lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: data,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true,
                            }
                        }]
                    }
                }
            });
        }

        function updateBarChart(data) {
            if (barChart) {
                barChart.destroy();
            }

            barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: data,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true,
                            }
                        }]
                    }
                }
            });
        }
    });
</script>
@endpush





