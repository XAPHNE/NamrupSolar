@extends('layouts.adminLTE')

@section('title', 'Update Major Activity')

@section('page_title', 'Update Major Activity')

@section('breadcrumb')
    <li class="breadcrumb-item active">Update Major Activity</li>
@stop

@section('content')
    <div class="row">
        <!-- Left side: Display name and image -->
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" readonly class="form-control" id="name" value="{{ $majorActivity->name }}">
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <div class="card" id="image">
                    <div class="card-body text-center">
                        <img src="{{ asset($majorActivity->image_path) }}" height="138px">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side: Form to update scope, completed, and unit -->
        <div class="col-sm-6">
            <form action="{{ route('home.update', $majorActivity->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="scope">Scope:</label>
                    <input type="number" class="form-control" id="scope" name="scope" value="{{ $majorActivity->scope }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="completed">Completed:</label>
                    <input type="number" class="form-control" id="completed" name="completed" value="{{ $majorActivity->completed }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="unit">Unit:</label>
                    <input type="text" readonly class="form-control" id="unit" name="unit" value="{{ $majorActivity->unit }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update {{ $majorActivity->name }} Major Activity</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')    
@endpush

@push('scripts')
@endpush