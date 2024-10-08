<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/img/Logo1222.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
        <span style="display:block; text-align:center; width:80%; margin: 0 auto" class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add your sidebar menu items here -->
                @if (auth()->user()->isAdmin || auth()->user()->isVendor)
                    <li class="nav-item">
                        <a href="{{ url('home') }}" class="nav-link {{ Request::is('home*') ? 'active' : '' }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Home
                                {{-- <i class="right fas fa-angle-left"></i> --}}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->isAdmin)
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Request::is('user-management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ url('drawings') }}" class="nav-link {{ Request::is('drawings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-palette"></i>
                        <p>
                            Drawings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('invoices') }}" class="nav-link {{ Request::is('invoices*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Invoice
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('supplies') }}" class="nav-link {{ Request::is('supplies*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck-moving"></i>
                        <p>
                            Supply
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('project-timeline') }}" class="nav-link {{ Request::is('project-timeline*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Project Timeline
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
