@php
    $title = \Arr::get($data, 'name');
    $shortDescription = \Arr::get($data, 'short_description', '');
    $description = \Arr::get($data, 'description', '');
    $titleShortDescription = $title . ($shortDescription ? ' | ' . $shortDescription : '');
    $heroUrl = url('images/hero.jpg');
    $tags = collect(Arr::get($data, 'products', []))->pluck('product_tags')->flatten()->unique()->toArray();
    $products = collect(Arr::get($data, 'products', []));
    $contacts = collect(Arr::get($data, 'contacts', []));
    $contactSize = max(3, intval(12 / count($contacts)));
    $icon = 5;
    $logoUrl = \Arr::get($data, 'logo.url', null);
@endphp
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $titleShortDescription }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ \Arr::get($data, 'logo.url') }}" rel="icon">
    <link href="{{ \Arr::get($data, 'logo.url') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ url('libs/sahel-font-master/dist/Farsi-Digits/font-face-FD.css') }}" />

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ url('libs/bootstrap/dist/css/bootstrap.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ url('libs/aos/dist/aos.css') }}">
    <link rel="stylesheet" href="{{ url('libs/glightbox/dist/css/glightbox.min.css') }}">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">

    <style>

    </style>

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ url('css/MyResume.css') }}">

    @yield('POS_HEAD')
</head>

<body dir="rtl" class="index-page">

    <header id="header" class="header d-flex flex-column justify-content-center">

        <i class="header-toggle d-xl-none bi bi-list"></i>
        <nav id="navmenu" class="navmenu">
            <ul dir="ltr">
                <li>
                    <a href="#hero">
                        <i class="bi bi-house-check"></i>
                        <span>{{ $title }}</span>
                    </a>
                </li>
                <li>
                    <a href="#portfolio">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>محصولات {{ $title }}</span>
                    </a>
                </li>
                @if ($contacts)
                    <li>
                        <a href="#contact">
                            <i class="bi bi-telephone"></i>
                            <span>ارتباط با ما</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="#footer">
                        <i class="bi bi-people"></i>
                        <span>درباره ما</span>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section light-background">

            <img src="{{ $heroUrl }}" alt="">

            <div class="container" data-aos="zoom-out">
                <div class="row">
                    <div class="col-lg-9">
                        <span class="h1 typed" data-typed-items="{{ implode(', ', $tags) }}"></span>
                        <h1 class="text-dark d-inline-block me-3">{{ $title }}</h1>
                        <h2 class="text-secondary">{{ $shortDescription }}</h2>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Portfolio Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title pb-4" data-aos="fade-up">
                <h2>محصولات {{ $title }}</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">همه محصولات {{ $title }}</li>
                        <br>
                        @foreach ($tags as $tag)
                            <li data-filter=".filter-{{ crc32($tag) }}" class="fs-5">{{ $tag }}</li>
                        @endforeach
                    </ul><!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container mt-2" data-aos="fade-up" data-aos-delay="200">
                        @foreach ($products as $product)
                            <div
                                class="border pt-3 mt-0 col-sm-6 col-md-4 col-lg-3 isotope-item @foreach ($product['product_tags'] as $productTag) {{ ' filter-' . crc32($productTag) }} @endforeach ">
                                @if (count($product['images']) == 1)
                                    <img class="w-100 pb-3 rounded" src="{{ $product['images'][0]['url'] }}"
                                        alt="{{ $product['name'] }}">
                                @elseif (count($product['images']) > 1)
                                    <div id="product-carousel-{{ $product['code'] }}" class="carousel pb-3 carousel-dark slide"
                                        data-bs-ride="true">
                                        <div class="carousel-inner">
                                            @foreach ($product['images'] as $productImage)
                                                <div class="carousel-item active">
                                                    <img class="w-100 pb-2 rounded" src="{{ $productImage['url'] }}"
                                                        alt="{{ $product['name'] }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#product-carousel-{{ $product['code'] }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#product-carousel-{{ $product['code'] }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="card-body mb-auto">
                                    <h5 class="card-title font-weight-bold pb-3">{{ $product['name'] }}</h5>
                                    @if ($product['product_properties'])
                                        <p class="card-text pb-3">
                                            @foreach ($product['product_properties'] as $property)
                                                <strong>{{ $property['property_key'] }}:</strong>
                                                {{ implode(', ', $property['property_values']) }}<br>
                                            @endforeach
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section><!-- /Portfolio Section -->

        @if ($contacts)
            <section id="contact" class="contact section">

                <!-- Section Title -->
                <div class="container section-title aos-init aos-animate pb-1" data-aos="fade-up">
                    <h2>ارتباط با ما</h2>
                </div><!-- End Section Title -->

                <div class="container" data-aos="fade" data-aos-delay="100">

                    <div class="row gy-4">
                        @foreach ($contacts as $contact)
                            @php
                                if ('address' == $contact['contact_type']) {
                                    $icon = 'bi bi-geo-alt';
                                } elseif ('telegram' == $contact['contact_type']) {
                                    $icon = 'bi bi-telegram';
                                } elseif ('whatsapp' == $contact['contact_type']) {
                                    $icon = 'bi bi-whatsapp';
                                } elseif ('email' == $contact['contact_type']) {
                                    $icon = 'bi bi-envelope';
                                } elseif ('instagram' == $contact['contact_type']) {
                                    $icon = 'bi bi-instagram';
                                } else {
                                    $icon = 'bi bi-telephone';
                                }
                            @endphp
                            <div class="col-lg-<?= $contactSize ?> pt-3">
                                <div class="info-item text-center">
                                    <div class="contact d-inline-block text-center ">
                                        <div class="d-flex justify-content-center">
                                            <i class="{{ $icon }} m-3"></i>
                                        </div>
                                        <h3>
                                            <?= $contact['contact_key'] ?>
                                        </h3>
                                        <p>
                                            <a href="<?= $contact['contact_link'] ?>" dir="ltr">
                                                <?= $contact['contact_value'] ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section><!-- /Contact Section -->
        @endif

    </main>

    <footer id="footer" class="footer position-relative light-background">
        <div class="container">
            <h3 class="sitename">{{ $title }}</h3>
            <p class="my-3">{{ $description }}</p>
            @if ($logoUrl)
                <img class="img-fluid" src="{{ $logoUrl }}" alt="{{ $title }}">
            @endif
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="{{ url('libs/aos/dist/aos.js') }}"></script>
    <script src="{{ url('libs/typed.js/dist/typed.umd.js') }}"></script>
    <script src="{{ asset('libs/@srexi/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="{{ asset('libs/glightbox/dist/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('libs/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('libs/isotope-layout/dist/isotope.pkgd.min.js') }}"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="{{ asset('js/MyResume.js') }}"></script>

    @yield('POS_END')
</body>

</html>
