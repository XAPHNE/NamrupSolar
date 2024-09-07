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
    <div class="row justify-content-between">
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Contract Value</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>₹ 1,119</strong> Cr</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>₹ 214</strong> Cr</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Excavation</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-truck-pickup fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>15.42</strong> L cum</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>12.93</strong> L cum</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Tunnel Excavation</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-archway fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>4.90</strong> Km</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>3.81</strong> Km</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Strctural Steel</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>1049</strong> MT</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>566</strong> MT</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-between">
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Contract Value</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>₹ 1,119</strong> Cr</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>₹ 214</strong> Cr</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Contract Value</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>₹ 1,119</strong> Cr</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>₹ 214</strong> Cr</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Contract Value</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>₹ 1,119</strong> Cr</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>₹ 214</strong> Cr</p>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-info">
                <div class="card-hearder text-center pt-3">
                    <h5><strong>Contract Value</strong></h5>
                </div>
                <div class="card-body text-center">
                    {{-- <img src="{{ asset('dist/img/home/money.jpg') }}" width="130px"> --}}
                    <i class="fas fa-rupee-sign fa-3x" style="color: #74C0FC"></i>
                </div>
                <div class="card-footer">
                    <p style="font-size: 12px">Scope: <strong>₹ 1,119</strong> Cr</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="text-center">70%</span>
                        </div>
                    </div>
                    <p style="font-size: 12px">Completed: <strong>₹ 214</strong> Cr</p>
                </div>
            </div>
        </div>
    </div>
@endsection
