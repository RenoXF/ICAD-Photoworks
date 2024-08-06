<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/dist/tabler-icons.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css"> --}}
    <style>
        :root {
            --tblr-font-sans-serif: 'Inter';
        }
    </style>
    @stack('css')
</head>

<body>
    <div class="page">
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('home') }}">
                        ICAD Photoworks
                    </a>
                </h1>
                @auth
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                {{-- <span class="avatar avatar-sm">PK</span> --}}
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ $user?->fullname ?? '-' }}</div>
                                    <div class="mt-1 small text-secondary">{{ $user?->role ?? '-' }}</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
                            </div>
                        </div>
                    </div>
                @endauth
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="icon ti ti-home"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Home
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('catalog.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="icon ti ti-category"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Katalog
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('schedule.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="icon ti ti-calendar"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Jadwal
                                    </span>
                                </a>
                            </li>
                            @guest
                                <div class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="icon ti ti-login"></i>
                                        </span>
                                        <span class="nav-link-title">
                                            Login
                                        </span>
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="icon ti ti-login-2"></i>
                                        </span>
                                        <span class="nav-link-title">
                                            Register
                                        </span>
                                    </a>
                                </div>
                            @endguest
                            @auth
                                @if ($isAdmin || $isOwner)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('user.index') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="icon ti ti-user"></i>
                                            </span>
                                            <span class="nav-link-title">
                                                Pelanggan
                                            </span>
                                        </a>
                                    </li>
                                @endif
                                @if ($isAdmin || $isClient)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('booking.index') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="icon ti ti-book"></i>
                                            </span>
                                            <span class="nav-link-title">
                                                Pesanan
                                            </span>
                                        </a>
                                    </li>
                                @endif
                                @if ($isAdmin || $isClient || $isOwner)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('transaction.index') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="icon ti ti-credit-card-pay"></i>
                                            </span>
                                            <span class="nav-link-title">
                                                Transaksi
                                            </span>
                                        </a>
                                    </li>
                                @endif
                                @if ($isAdmin || $isOwner)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('report.index') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="icon ti ti-file-spreadsheet"></i>
                                            </span>
                                            <span class="nav-link-title">
                                                Laporan
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            @yield('content')
        </div>
        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        {{-- <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright © 2024
                                <a href="." class="link-secondary">{{ config('app.name') }}</a>.
                                All rights reserved.
                            </li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </script>
    <script>
        const initSelect2 = () => {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        }
        $(document).ready(initSelect2);
        $('.modal').on('show.bs.modal', initSelect2)
        $('.modal').on('shown.bs.modal', initSelect2)
    </script>
    @stack('js')
</body>

</html>
