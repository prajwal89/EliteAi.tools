<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title')</title>
    <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />
    {{-- https://muffingroup.com/betheme/elements/icons/ --}}
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    @yield('head')
    @include('partials.favicon-tags')
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/">{{ config('app.name') }}</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tag"></i></div>
                            Dashboard
                        </a>

                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Users
                        </a>

                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-hashtag"></i></div>
                            Categories
                        </a>


                        <a class="nav-link" href="{{ route('admin.tools.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Tools
                        </a>

                        <a class="nav-link" href="{{ route('admin.tools-to-process.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-code"></i></div>
                            Tools to process
                        </a>

                        <a class="nav-link" href="{{ route('admin.tools.import') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-magic"></i></div>
                            Import
                        </a>

                        <a class="nav-link" href="{{ route('admin.blogs.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-code"></i></div>
                            Blogs
                        </a>


                        <a class="nav-link" href="{{ route('admin.tags.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-hashtag"></i></div>
                            Tags
                        </a>

                        <a class="nav-link" href="{{ route('admin.top-searches.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-hashtag"></i></div>
                            Top Searches
                        </a>

                        <a class="nav-link" href="{{ route('admin.meilisearch.control-panel') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-hashtag"></i></div>
                            Meilisearch
                        </a>

                        <a class="nav-link" href="{{ config('log-viewer.route_path') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Logs
                        </a>
                    </div>
                </div>


                {{-- <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div> --}}
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h3 class="my-2">@yield('title')</h3>

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    @foreach (['success', 'info', 'error'] as $alertType)
                        @if (session($alertType))
                            <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
                                {!! session($alertType) !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                    @endforeach

                    @yield('content')
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('admin/js/scripts.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> --}}
    {{-- <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> --}}
    {{-- <script src="js/datatables-simple-demo.js"></script> --}}

    @yield('scripts')
</body>

</html>
