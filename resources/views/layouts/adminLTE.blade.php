<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    <!-- DataTable -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    {{-- <link href="{{ asset('plugins/datatabls.net/datatables.min.css') }}" rel="stylesheet"> --}}
    <!-- DataTables Buttons extension -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css"> --}}

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
        #map {
            height: 400px; /* Set a height for the map */
            width: 100%; /* Set full width */
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
    {{-- <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div> --}}

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Sidebar Container -->
    @include('partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        @yield('breadcrumb')
                        {{-- <li class="breadcrumb-item active">@yield('page_title', 'Dashboard')</li> --}}
                    </ol>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')

                <!-- Salient Features Modal -->
                <div class="modal fade" id="salientFeaturesModal">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title" id="modalTitle">Salient Features</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body row">
                                <div class="col sm-6">
                                    <!-- Map container -->
                                    <div id="map"></div>
                                </div>
                                <div class="col sm-6">
                                    <table id="salientFeaturesTable" class="table nowrap table-borderless table-striped table-sm" style="width: 100%">
                                        <thead>
                                            <tr class="table-primary diaplay-block">
                                                <th>Project Facts</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Project Location</td>
                                                <td>NTPS, Dibrugarh</td>
                                            </tr>
                                            <tr>
                                                <td>Capacity</td>
                                                <td>25 MW</td>
                                            </tr>
                                            <tr>
                                                <td>Land Available</td>
                                                <td>107 acres</td>
                                            </tr>
                                            <tr>
                                                <td>CUF (ac)</td>
                                                <td>23.27%</td>
                                            </tr>
                                            <tr>
                                                <td>Gross Energy Generation</td>
                                                <td>50.96 MU</td>
                                            </tr>
                                            <tr>
                                                <td>Total Project Cost</td>
                                                <td>115.86 Cr</td>
                                            </tr>
                                            <tr>
                                                <td>Debt:Equity</td>
                                                <td>70:30</td>
                                            </tr>
                                            <tr>
                                                <td>Loan Amount</td>
                                                <td>81.10 Cr</td>
                                            </tr>
                                            <tr>
                                                <td>Equity Amount</td>
                                                <td>34.76 Cr</td>
                                            </tr>
                                            <tr>
                                                <td>Equity Contribution</td>
                                                <td>
                                                    APGCL - 17.72 Cr (51%)<br>
                                                    OIL - 17.03 Cr (49%)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Levelized Cost of Generation (25 Years)</td>
                                                <td>Replace</td>
                                            </tr>
                                            <tr>
                                                <td>Project IRR (Pre-Tax)</td>
                                                <td>12.58%</td>
                                            </tr>
                                            <tr>
                                                <td>Equity IRR (Post-Tax)</td>
                                                <td>12.04%</td>
                                            </tr>
                                            <tr>
                                                <td>Average DSCR</td>
                                                <td>1.21</td>
                                            </tr>
                                            <tr>
                                                <td>Payback Period</td>
                                                <td>11.85 Years</td>
                                            </tr>
                                            <tr>
                                                <td>NPV</td>
                                                <td>12.09 Cr</td>
                                            </tr>
                                            <tr>
                                                <td>Completion Date</td>
                                                <td>12 Months from LOA</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- jQuery UI 1.14 -->
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
{{-- <script src="{{ asset('dist/js/demo.js') }}"></script> --}}
{{-- <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script> --}}

<!-- DataTables JS -->
{{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
<!-- DataTables Buttons JS -->
<script src="//cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
{{-- <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Sweet Alert JS -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    const targetDate = new Date("{{ '2025-03-31 23:59:59' }}").getTime();
</script>
<script src="{{ asset('dist/js/custom/countdown-timer.js') }}"></script>

<!-- Leaflet.js Script -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    $(document).ready(function () {
        $('#salientFeaturesButton').on('click', function () {
            $('#salientFeaturesModal').modal('show');

            setTimeout(function () {
                // Initialize the map once the modal is opened
                var map = L.map('map').setView([27.195510858768763, 95.37858090157266], 15); // Set your latitude and longitude here

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add a marker at the location
                L.marker([27.195510858768763, 95.37858090157266]).addTo(map)
                    .bindPopup('Location: 27.195510858768763, 95.37858090157266')
                    .openPopup();

                // Ensure map size is recalculated correctly when modal is shown
                map.invalidateSize();
            }, 500); // Slight delay to ensure the modal is fully open
        });

        new DataTable('#salientFeaturesTable', {
            info: false,
            paging: false,
            ordering: false,
            searching: false,
            scrollCollapse: true,
            scrollY: '50vh',
        })
    });
</script>

@stack('scripts')
</body>
</html>
