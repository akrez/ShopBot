<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ \Arr::get($data, 'logo.url') }}">

    <title>
        {{ \Arr::get($data, 'name') .
            (\Arr::get($data, 'short_description') ? ' | ' . \Arr::get($data, 'short_description') : '') }}
    </title>

    <!-- CSS files -->
    <link rel="stylesheet" href="{{ url('libs/bootstrap/dist/css/bootstrap.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/vazirmatn/Vazirmatn-font-face.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/@fortawesome/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('blog.css') }}">

    @yield('POS_HEAD')
</head>

<body dir="rtl">
    @yield('POS_BEGIN')
    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-3">
                @if (\Arr::get($data, 'logo.url'))
                    <div class="row pb-2">
                        <div class="col-sm-12">
                            <a href="" style="text-align: center;">
                                <img class="w-100 rounded" alt="{{ \Arr::get($data, 'name') }}"
                                    src="{{ \Arr::get($data, 'logo.url') }}" style="margin: auto;">
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="btn-group-vertical w-100" role="group">
                            @foreach (collect(Arr::get($data, 'products', []))->pluck('product_tags')->flatten()->unique()->toArray() as $tagKey => $tag)
                                <input type="radio" class="btn-check" name="vbtn-radio"
                                    id="vbtn-checbox-{{ $tagKey }}" autocomplete="off">
                                <label class="btn btn-outline-dark" for="vbtn-checbox-{{ $tagKey }}">
                                    <h4 class="m-1">{{ $tag }}</h4>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 mb-4">
                <div class="mb-4">
                    <h1 class="d-inline-block">{{ \Arr::get($data, 'name') }}</h1>
                    <h2 class="d-inline-block ms-2 text-secondary">{{ \Arr::get($data, 'short_description') }}</h2>
                    <h4 class="text-justify">{{ \Arr::get($data, 'description') }}</h4>
                </div>
                <div class="container-fluid">
                    <div class="row equal">
                        @foreach (Arr::get($data, 'products', []) as $product1)
                            @foreach (Arr::get($data, 'products', []) as $product)
                                <div class="thumbnail border pt-3 pb-3 col-sm-6 col-md-4 col-lg-3">
                                    @if (isset($product['images'][0]))
                                        <img class="w-100 pb-2 rounded" src="{{ $product['images'][0]['url'] }}"
                                            alt="{{ $product['name'] }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold pb-2">{{ $product['name'] }}</h5>
                                        <p class="card-text">
                                            @foreach ($product['product_properties'] as $property)
                                                <strong>{{ $property['property_key'] }}:</strong>
                                                {{ implode(', ', $property['property_values']) }}<br>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 pt-2 text-justify">
                </div>
                <div class="col-sm-3" dir="ltr">
                    <h6 class="text-uppercase fw-bold mb-4">ارتباط با ما</h6>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
    @yield('POS_END')
</body>

</html>
