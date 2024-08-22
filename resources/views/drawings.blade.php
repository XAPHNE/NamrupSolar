@extends('layouts.adminLTE')

@section('title', 'Drawings')

@section('page_title', 'Drawings')

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{ route('drawings.index') }}">Drawings</a></li> --}}
    <li class="breadcrumb-item active">Drawings</li>
@stop

@section('content')
    
        @foreach($drawings as $drawing)
            <button class="btn btn-primary m-1">{{ $drawing->name }}</button>
        @endforeach
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
@endpush

