<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="shortcut icon" href="{{asset('img/shorticon.png')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/admin.min.css') }}" rel="stylesheet">
</head>
<body class="light" @auth id="page-top" @endauth>

@auth
<div id="wrapper">

    <ul class="navbar-nav admin-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}l">
            <div class="sidebar-brand-text mx-3">HERMES</div>
        </a>

        <hr class="sidebar-divider mb-4">

        <div class="sidebar-heading">
            Inventory
        </div>

        <li class="nav-item @if(Route::currentRouteName() == 'dashboard') active @endif">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="far fa-chart-bar"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'sales') active @endif">
            <a class="nav-link"  href="{{route('sales')}}">
                <i class="far fa-chart-bar"></i>
                <span>Sales</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'items' || Route::currentRouteName() == 'edit.item') active @endif">
            <a class="nav-link"  href="{{route('items')}}">
                <i class="fas fa-plus mr-3 text-tertiary"></i>
                <span>Add Items</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'transfer.in') active @endif">
            <a class="nav-link"  href="{{route('transfer.in')}}">
                <i class="fas fa-level-down-alt mr-3 text-tertiary"></i>
                <span>Transfer In</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'deduct.items') active @endif">
            <a class="nav-link"  href="{{route('deduct.items')}}">
                <i class="fas fa-level-up-alt mr-3 text-tertiary"></i>
                <span>Deduct Items</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse"
                aria-expanded="true" aria-controls="categoryCollapse">
                <i class="fas fa-box-open"></i>
                <span>Manage Category</span>
            </a>
            <div id="categoryCollapse" class="collapse @if(Route::currentRouteName() == 'categories' || Route::currentRouteName() == 'sub.categories') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'categories') active @endif" href="{{route('categories')}}"><i class="fas fa-sitemap mr-2 text-tertiary"></i><span>Category</span></a>
                    <a class="collapse-item text-white  @if(Route::currentRouteName() == 'sub.categories') active @endif" href="{{route('sub.categories')}}"><i class="fas fa-sitemap mr-2 text-tertiary"></i><span>Sub Category</span></a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#itemsCollapse"
                aria-expanded="true" aria-controls="itemsCollapse">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
            <div id="itemsCollapse" class="collapse @if(Route::currentRouteName() == 'user' || Route::currentRouteName() == 'add.user' || Route::currentRouteName() == 'edit.user') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'user' || Route::currentRouteName() == 'edit.user') active @endif" href="{{route('user')}}"><i class="fas fa-user mr-2 text-tertiary"></i><span>Users</span></a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'add.user') active @endif" href="{{route('add.user')}}"><i class="fas fa-user-plus mr-2 text-tertiary"></i><span>Add New User</span></a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Actions
        </div>
        <li class="nav-item ">
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
        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Report</span>
            </a>
        </li>

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
                                src="{{asset('img/admin-avatar.png')}}">
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
                </div>
                {{-- errors MESSAGE --}}
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- success MESSAGE --}}
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-4" role="alert">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div>
                        {{ session()->get('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- error MESSAGE --}}
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-4" role="alert">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                    <div>
                        {{ session()->get('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

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
    @if(Route::currentRouteName() == 'dashboard')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    @endif
    <script src="{{asset('vendor/axios/axios.min.js')}}"></script>
    <script src="{{asset('vendor/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        if(document.querySelector('.alert .btn-close')){
            setTimeout(() => {
                document.querySelector('.alert .btn-close').click()
            }, 4000);
        }
    </script>
    @yield('script')
    @else
        @yield('content')
    @endauth
</body>
</html>
