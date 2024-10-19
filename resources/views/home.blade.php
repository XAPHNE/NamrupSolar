@extends('layouts.adminLTE')

@section('title', 'Home')

@section('page_title', 'Home')

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
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
            <div class="small-box bg-success">
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
            <div class="small-box bg-warning">
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
            <div class="small-box bg-danger">
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
            <div class="small-box bg-primary">
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
    <div class="pb-3 row">
        <div class="col-sm-12">
            {{-- Physical completion card --}}
            <div class="card">
                <div class="p-1 card-body">
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
            {{-- Financial completion Card --}}
            <div class="card">
                <div class="p-1 card-body">
                    <div class="progress" style="height:30px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="{{ round($totalFinancialProgress, 2) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($totalFinancialProgress, 2) }}%;">
                            <span class="text-center">Financial Completion: {{ round($totalFinancialProgress, 2) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($majorActivities->chunk(5) as $chunk)  <!-- Group the cards in chunks of 5 -->
        <div class="row justify-content-between">
            @foreach($chunk as $majorActivity)
                <div class="col-sm-2">
                    <!-- Wrap the card in an anchor tag to make it clickable -->
                    @if (auth()->user()->is_admin || auth()->user()->is_editor)
                    <a href="{{ route('home.edit', $majorActivity->id) }}" style="text-decoration: none; color: inherit;">
                    @endif
                        <div class="card 
                            @if($loop->iteration == 1) {
                                card-info
                            } @elseif ($loop->iteration == 2) {
                                card-success
                            } @elseif ($loop->iteration == 3) {
                                card-warning
                            } @elseif ($loop->iteration == 4) {
                                card-danger
                            } @elseif ($loop->iteration == 5) {
                                card-primary
                            } @else {
                                card-secondary
                            }
                            @endif" style="min-height: 197px">
                            <div class="p-0 m-0 text-center card-header" style="min-height: 50px">
                                <h7 class="p-0 m-0"><strong>{{ $majorActivity->name }}</strong></h7>
                            </div>
                            <div class="pt-1 pb-1 text-center card-body">
                                <img src="{{ asset($majorActivity->image_path) }}" width="130px">
                            </div>
                            <div class="pt-0 pb-0 card-footer">
                                <p class="m-0" style="font-size: 12px">Scope: <strong>{{ $majorActivity->scope }}</strong> {{ $majorActivity->unit }}</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="{{ ($majorActivity->completed / $majorActivity->scope) * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($majorActivity->completed / $majorActivity->scope) * 100 }}%">
                                        <span class="p-0 text-center">{{ round(($majorActivity->completed / $majorActivity->scope) * 100, 2) }}%</span>
                                    </div>
                                </div>
                                <p class="p-0 m-0" style="font-size: 12px">Completed: <strong>{{ $majorActivity->completed }}</strong> {{ $majorActivity->unit }}</p>
                            </div>
                        </div>
                    @if (auth()->user()->is_admin || auth()->user()->is_editor)
                    </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
@endsection

@push('scripts')
    <!-- Pass Blade variable to JavaScript to show countdown-timer -->
    {{-- <script>
        const targetDate = new Date("{{ $targetDate }}").getTime();
    </script> --}}
    {{-- <script src="{{ asset('dist/js/custom/countdown-timer.js') }}"></script> --}}
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
