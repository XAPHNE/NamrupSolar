@extends('layouts.adminLTE')

@section('title')
    Project Timeline
@endsection

@section('page_title')
    Project Timeline
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Project Timeline</li>
@endsection

@section('content')
    <div id="gantt_here" style='width:100%; height:100%;'></div>
@endsection

@push('styles')
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
    <style type="text/css">
        .content,
        .container-fluid {
            min-height: 445px;
            height: 100%;
            padding: 0px;
            margin: 0px;
            overflow: hidden;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <script type="text/javascript">
        gantt.init("gantt_here");
    </script>
@endpush
