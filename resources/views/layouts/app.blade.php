<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body @auth id="page-top" @endauth>

@auth
<div id="wrapper">

    <ul class="navbar-nav admin-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-text mx-3">HERMES</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <a href="{{route('dashboard')}}" class="nav-link" href="index.html">
                <i class="far fa-chart-bar"></i>
                <span>Dashboard (Sales)</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Management
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#itemsCollapse"
                aria-expanded="true" aria-controls="itemsCollapse">
                <i class="fas fa-box-open"></i>
                <span>Items</span>
            </a>
            <div id="itemsCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white" href="{{route('transfer.in')}}"><i class="fas fa-level-down-alt mr-3 text-tertiary"></i><span>Transfer In</span></a>
                    <a class="collapse-item text-white" href="{{route('transfer.out')}}"><i class="fas fa-level-up-alt mr-3 text-tertiary"></i><span>Transfer Out</span></a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="tables.html">
                <i class="fas fa-tasks"></i>
                <span>Item Quantity Check</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-calendar-check"></i>
                <span>Inventory Quantity Check</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Actions
        </div>

        <li class="nav-item">
            <a class="nav-link" href="tables.html">
                <i class="fas fa-fw fa-table"></i>
                <span>Inventory Adjustment</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Report</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#resourcesCollapse"
                aria-expanded="true" aria-controls="resourcesCollapse">
                <i class="fas fa-fw fa-folder"></i>
                <span>Resources</span>
            </a>
            <div id="resourcesCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white" href="login.html"><i class="fas fa-level-down-alt mr-3 text-tertiary"></i><span>Import</span></a>
                    <a class="collapse-item text-white" href="register.html"><i class="fas fa-level-up-alt mr-3 text-tertiary"></i><span>Export</span></a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Transaction Explorer</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Item Quantity Check</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="sidebar-card d-none d-lg-flex">
            <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="vencer">
            <p class="text-center mb-2">Have a Good day!</p>
            {{-- <p class="text-center mb-2">Support <strong>Me</strong>! I'm happy working with you.</p>
            <a class="btn text-light btn-sm" href="#" target="_blank">Buy me a Coffee</a> --}}
        </div>

    </ul>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <nav class="navbar navbar-expand navbar-light admin-nav topbar mb-4 static-top shadow">

                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-white-600 small">{{Auth::user()->name}}</span>
                            <img class="img-profile rounded-circle"
                                src="img/undraw_profile.svg">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in shadow"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-tertiary"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-tertiary"></i>
                                Activity Log
                            </a>
                            <div class="dropdown-divider gray-900"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-tertiary"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-light text-capitalize">{{str_replace("."," ", Route::currentRouteName())}}</h1>
                    <a href="#" class="d-none d-sm-inline-block btn text-light btn-sm shadow-sm">
                        <i class="fas fa-download fa-sm"></i> Generate Report
                    </a>
                </div>

                @yield('content')
            
            </div>

        </div>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body text-center my-4">
            <i class="far fa-question-circle logout-info"></i>
            <h5>Ready to Leave?</h5>
            <a class="btn text-light mt-4" href="#" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i class="fas fa-undo-alt mr-2"></i><span>Logout</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    </div>
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @else
        @yield('content')
    @endauth
</body>
</html>
