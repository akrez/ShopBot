<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    @hasSection('title')
        <title>@yield('title')</title>
    @else
        @hasSection('header')
            <title>@yield('header')@hasSection('subheader'){{ ' | ' }}@yield('subheader')@endif</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif
    @endif

    <!-- CSS files -->
    <link rel="stylesheet" href="{{ url('libs/bootstrap/dist/css/bootstrap.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/sweetalert2/dist/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/vazirmatn/Vazirmatn-font-face.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/@fortawesome/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('app.css') }}">

    @yield('POS_HEAD')
</head>

<body dir="rtl">
    @yield('POS_BEGIN')
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg z-1030">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">وبـلاگ فروشـگاهـی اکــرز</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('blogs.index') }}">{{ __('Blogs') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @if (\App\Facades\ActiveBlog::has())
        <nav class="navbar navbar-light bg-light navbar-expand-lg z-1030">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    @if (\App\Facades\ActiveBlog::get()->logoUrl())
                        <img class="pe-3 max-height-28-px" src="{{ \App\Facades\ActiveBlog::get()->logoUrl() }}">
                    @endif
                    {{ \App\Facades\ActiveBlog::name() }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('products.index') }}">
                                {{ __('Products') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Blog') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('contacts.index') }}">{{ __('Contacts') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('blog_logos.index') }}">{{ __('blog_logos') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('blogs.edit', ['id' => \App\Facades\ActiveBlog::attr('id')]) }}">{{ __('Edit') }}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('port.index') }}">
                                {{ __('Port') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
    <div class="container mt-3">
        @hasSection('header')
            <h1 class="fs-2 my-4">
                @yield('header')
                @hasSection('subheader')
                    <small class="text-muted">@yield('subheader')</small>
                @endif
            </h1>
        @endif
        @yield('content')
    </div>
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        @if (session('swal-success'))
            Swal.fire({{ Illuminate\Support\Js::from([
                'text' => session('swal-success'),
                'icon' => 'success',
                'timer' => 5000,
                'showCloseButton' => true,
                'showConfirmButton' => false,
                'timerProgressBar' => true,
            ]) }});
        @endif
    </script>
    @yield('POS_END')
</body>

</html>
