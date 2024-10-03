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
@endpush

@push('scripts')
<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
<script type="text/javascript">
    gantt.init("gantt_here");
</script>
@endpush
