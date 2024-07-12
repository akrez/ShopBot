<!doctype html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('images/logo.svg') }}">
    <!-- CSS files -->
    <link href="{{ url('tabler/css/tabler.rtl.min.css') }}" rel="stylesheet" />
    <link href="{{ url('tabler/css/tabler-flags.rtl.min.css') }}" rel="stylesheet" />
    <link href="{{ url('tabler/css/tabler-payments.rtl.min.css') }}" rel="stylesheet" />
    <link href="{{ url('tabler/css/tabler-vendors.rtl.min.css') }}" rel="stylesheet" />
    <link href="{{ url('vazirmatn/Vazirmatn-font-face.css') }}" rel="stylesheet" />
    <link href="{{ url('fontawesome/css/all.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --tblr-font-sans-serif: 'Vazirmatn';
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('home') }}">{{ config('app.name') }}</a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ @route('login') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Login') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ @route('register') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Register') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    @else
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                <span class="avatar avatar-sm"
                                    style="background-image: url({{ url('images/logo.svg') }})"></span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="mt-1 small text-secondary">{{ Auth::user()->name }}</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Logout') }}
                                    </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ @route('home') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Home
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @hasSection('pretitle')
                                <div class="page-pretitle">
                                    @yield('pretitle')
                                </div>
                            @endif
                            @hasSection('title')
                                <div class="page-title">
                                    @yield('title')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{ url('tabler/js/tabler.min.js') }}" defer></script>
</body>

</html>
