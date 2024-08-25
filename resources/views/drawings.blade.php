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
                     data-drawings="{{ $drawing->total_drawings }}" 
                     data-scope="{{ $drawing->total_drawings_scope }}" 
                     data-submitted="{{ $drawing->total_submitted_drawings }}" 
                     data-approved="{{ $drawing->total_approved_drawings }}"
                     style="height: 60px; margin: 0 auto;">
                    <div class="p-3 text-center">
                        <p class="text-center mb-0" style="font-size: 14px;">{{ $drawing->name }}</p>
                        <a href="#" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-sm-6">
            <!-- LINE CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Line Chart</h3>
    
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}
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

                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}
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
                                <th>Submitted</th>
                                <th>Approved</th>
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
            searching: false,
            lengthChange: false,
            autoWidth: false,
            columns: [
                { data: 'drawing_details_no' },
                { data: 'drawing_details_name' },
                { data: 'isScopeDrawing', render: function(data, type, row) {
                        return data ? 'Yes' : 'No';
                    } 
                },
                { data: 'isSubmitted', render: function(data, type, row) {
                        return data ? 'Yes' : 'No';
                    }
                },
                { data: 'isApproved', render: function(data, type, row) {
                        return data ? 'Yes' : 'No';
                    }
                }
            ],
            data: []
        });

        // Default line chart data
        var defaultLineData = {
            labels  : ['Total Drawings', 'Scope', 'Submitted', 'Approved'],
            datasets: [
                {
                    label               : 'Drawing Data',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius         : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [0, 0, 0, 0]
                }
            ]
        };

        var lineChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : true,
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Ensure the Y-axis starts at zero
                    },
                    gridLines : {
                        display : true,
                    }
                }]
            },
            datasetFill: false
        };

        // Default bar chart data
        var defaultBarData = {
            labels  : ['Scope Drawings', 'Submitted Drawings', 'Approved Drawings'], // Updated labels
            datasets: [
                {
                    label               : 'Count',
                    backgroundColor     : ['rgba(60,141,188,0.9)', 'rgba(210, 214, 222, 1)', 'rgba(0, 166, 90, 0.9)'],
                    borderColor         : ['rgba(60,141,188,0.8)', 'rgba(210, 214, 222, 0.8)', 'rgba(0, 166, 90, 0.8)'],
                    data                : [0, 0, 0] // Updated default data
                }
            ]
        };

        var barChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : true,
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Ensure the Y-axis starts at zero
                    },
                    gridLines : {
                        display : true,
                    }
                }]
            }
        };

        function updateLineChart(data) {
            if (lineChart) {
                lineChart.destroy(); // Destroy the old chart before creating a new one
            }

            lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: data,
                options: lineChartOptions
            });
        }

        function updateBarChart(data) {
            if (barChart) {
                barChart.destroy(); // Destroy the old chart before creating a new one
            }

            barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: data,
                options: barChartOptions
            });
        }

        // Initialize the charts with default data
        updateLineChart(defaultLineData);
        updateBarChart(defaultBarData);

        // Handle card clicks
        $('.clickable-card').on('click', function () {
            var drawingId = $(this).data('id');
            var drawingName = $(this).data('name');

            // Update line chart data
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
                            $(this).data('drawings'), 
                            $(this).data('scope') || 0, 
                            $(this).data('submitted') || 0, 
                            $(this).data('approved') || 0
                        ]
                    }
                ]
            };

            // Fetch data for the bar chart
            $.ajax({
                url: '/drawings/' + drawingId, // Using the resource route's show method
                method: 'GET',
                success: function(response) {
                    // Update the DataTable with new data
                    dataTable.clear().rows.add(response.details).draw();
                    // Update the charts with the new data
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
                }
            });
        });
    });
</script>
@endpush

