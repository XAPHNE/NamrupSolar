@extends('layouts.adminLTE')

@section('title', 'Home')

@section('page_title', 'Home')

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Your content goes here -->
    <div class="row justify-content-between">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>01 Sep 2020</h4>
                    <p style="font-size: 14px">Contractual Start Date</p>
                </div>
                {{-- <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>11 Jun 2021</h4>
                    <p style="font-size: 14px">Land Handover Date</p>
                </div>
                {{-- <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>31 Aug 2024</h4>
                    <p style="font-size: 14px">Contractual End Date</p>
                </div>
                {{-- <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>31 Dec 2024</h4>
                    <p style="font-size: 14px">Best Effort Completion</p>
                </div>
                {{-- <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>14 Jul 2025</h4>
                    <p style="font-size: 14px">EOT-1 Completion Date</p>
                </div>
                {{-- <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col-sm-12">
            {{-- Physical completion card --}}
            <div class="card">
                <div class="card-body p-1">
                    <div class="progress" style="height:30px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                            aria-valuenow="{{ round($totalProgress, 2) }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100" 
                            style="width: {{ round($totalProgress, 2) }}%;">
                            <span class="text-center">Physical Completion: {{ round($totalProgress, 2) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Time completion Card --}}
            <!-- <div class="card">
                <div class="card-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">Time Completion: 70%</span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    @foreach($majorActivities->chunk(5) as $chunk)  <!-- Group the cards in chunks of 5 -->
        <div class="row justify-content-between">
            @foreach($chunk as $majorActivity)
                <div class="col-sm-2">
                    <!-- Wrap the card in an anchor tag to make it clickable -->
                    <a href="{{ route('home.edit', $majorActivity->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="card card-info" style="min-height: 197px">
                            <div class="card-header text-center m-0 p-0" style="min-height: 50px">
                                <h7 class="m-0 p-0"><strong>{{ $majorActivity->name }}</strong></h7>
                            </div>
                            <div class="card-body text-center pt-1 pb-1">
                                <img src="{{ asset($majorActivity->image_path) }}" width="130px">
                            </div>
                            <div class="card-footer pt-0 pb-0">
                                <p class="m-0" style="font-size: 12px">Scope: <strong>{{ $majorActivity->scope }}</strong> {{ $majorActivity->unit }}</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="{{ ($majorActivity->completed / $majorActivity->scope) * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($majorActivity->completed / $majorActivity->scope) * 100 }}%">
                                        <span class="text-center p-0">{{ round(($majorActivity->completed / $majorActivity->scope) * 100, 2) }}%</span>
                                    </div>
                                </div>
                                <p class="m-0 p-0" style="font-size: 12px">Completed: <strong>{{ $majorActivity->completed }}</strong> {{ $majorActivity->unit }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection

@push('scripts')
    <!-- Pass Blade variable to JavaScript to show countdown-timer -->
    <script>
        const targetDate = new Date("{{ $targetDate }}").getTime();
    </script>
    <script src="{{ asset('dist/js/custom/countdown-timer.js') }}"></script>
    <script>
        // Check if there's a success message in the session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            });
        @endif
    
        // Check if there's an error message in the session
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
@endpush