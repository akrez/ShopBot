@php
    $title = \Arr::get($data, 'name');
    $shortDescription = \Arr::get($data, 'short_description', '');
    $titleShortDescription = $title . ($shortDescription ? ' | ' . $shortDescription : '');
    $heroUrl = url('images/hero.jpg');
    $tags = collect(Arr::get($data, 'products', []))->pluck('product_tags')->flatten()->unique()->toArray();
    $products = collect(Arr::get($data, 'products', []));
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
    <link rel="stylesheet" href="{{ url('libs/vazirmatn/Vazirmatn-font-face.css') }}" />

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ url('libs/bootstrap/dist/css/bootstrap.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ url('libs/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ url('libs/aos/dist/aos.css') }}">
    <link rel="stylesheet" href="{{ url('libs/glightbox/dist/css/glightbox.min.css') }}">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">

    <style>

    </style>

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ url('MyResume.css') }}">

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
                @if (Arr::get($data, 'contacts'))
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
                <div class="row justify-content-center">
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
                        @foreach ($tags as $tag)
                            <li data-filter=".filter-{{ crc32($tag) }}">{{ $tag }}</li>
                        @endforeach
                    </ul><!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                        @foreach ($products as $product)
                            <div class="thumbnail border pt-3 pb-3 col-sm-6 col-md-4 col-lg-3 isotope-item @foreach ($product['product_tags'] as $productTag) {{ ' filter-' . crc32($productTag) }} @endforeach ">
                                @if (isset($product['images'][0]))
                                    <img class="w-100 pb-2 rounded" src="{{ $product['images'][0]['url'] }}"
                                        alt="{{ $product['name'] }}">
                                @endif
                                <div class="card-body mb-auto">
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
                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section><!-- /Portfolio Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Address</h3>
                                <p>A108 Adam Street, New York, NY 535022</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Call Us</h3>
                                <p>+1 5589 55488 55</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p>info@example.com</p>
                            </div>
                        </div><!-- End Info Item -->

                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Your Name" required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Your Email" required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer position-relative light-background">
        <div class="container">
            <h3 class="sitename">Brandon Johnson</h3>
            <p>Et aut eum quis fuga eos sunt ipsa nihil. Labore corporis magni eligendi fuga maxime saepe commodi
                placeat.</p>
            <div class="social-links d-flex justify-content-center">
                <a href=""><i class="bi bi-twitter-x"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-skype"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
            <div class="container">
                <div class="copyright">
                    <span>Copyright</span> <strong class="px-1 sitename">Alex Smith</strong> <span>All Rights
                        Reserved</span>
                </div>
                <div class="credits">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you've purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </div>
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
    <script src="{{ asset('MyResume.js') }}"></script>

    @yield('POS_END')
</body>

</html>
