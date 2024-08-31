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
                    <h3 class="card-title">Drawing Details</h3>
                </div>
                <div class="card-body">
                    <table id="drawingDetailsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Scope</th>
                                <th>Submitted At</th>
                                <th>Submitted By</th>
                                <th>Comment</th>
                                <th>Commented At</th>
                                <th>Commented By</th>
                                <th>Resubmitted At</th>
                                <th>Approved At</th>
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
@endsection

@push('scripts')
<script>
    $(function () {
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
            autoWidth: true,
            columns: [
                { data: 'drawing_details_no', title: 'Number' },
                { data: 'drawing_details_name', title: 'Name' },
                { data: 'isScopeDrawing', title: 'Scope', render: function(data, type, row) {
                        return data === 'Yes' ? 'Yes' : 'No';
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
                        return data ? data : 'N/A';
                    }
                },
                { data: 'commented_at', title: 'Commented At', render: function(data) {
                        return data ? data : 'N/A';
                    } 
                },
                { data: 'commented_by', title: 'Commented By', render: function(data) {  // Display the commenter's name
                        return data ? data : 'N/A';
                    } 
                },
                { data: 'resubmitted_at', title: 'Resubmitted At', render: function(data) {
                        return data ? data : 'N/A';
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
            data: []
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
                    // Update the DataTable with new data
                    dataTable.clear().rows.add(response.details).draw();

                    // Update the charts with the new data
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

                    updateLineChart(lineChartData);
                    updateBarChart(barChartData);
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




