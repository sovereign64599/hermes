@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md text-tertiary text-capitalize mb-1">
                                    Total Items
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray">{{$totalItems}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box text-tertiary fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md text-tertiary text-capitalize mb-1">
                                    Users
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray">{{$users}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users text-tertiary fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md text-tertiary text-capitalize mb-1">
                                    Items Delivered
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray">{{$delivered}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck-loading text-tertiary fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md text-tertiary text-capitalize mb-1">
                                    Total Sales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray">₱{{number_format($totalSales), 2}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line text-tertiary fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 text-tertiary">Sales Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    
@endsection
