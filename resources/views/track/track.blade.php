<!doctype html>
<html class="no-js" lang="en">

<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- title of site -->
    <title>Eco Craft</title>
    <link rel="shortcut icon" type="image/icon"
        href="{{ asset('assets/logo/Logo_Eco-Craft-removebg-preview 1.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootsnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    <style>
        /* Styling untuk navbar agar sejajar ke kiri */
        .navbar-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
            width: auto;
        }

        /* Menambahkan jarak antar item menu */
        .navbar-nav li {
            padding: 0px 0.5px;
        }

        /* Styling untuk link menu navbar */
        .navbar-nav li a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .navbar-nav li a:hover {
            color: #f56c42;
        }

        .dropdown-menu {
            min-width: 250px;
            border-radius: 8px;
            padding: 10px 0;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            margin-right: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            color: #333;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }

        .dropdown-item .fa {
            font-size: 18px;
            color: #5c5c5c;
        }

        /* Gaya untuk gambar profil */
        .profile-img {
            width: 40px;
            height: 40px;
            margin-top: -5px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Styling untuk navbar-header */
        .navbar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .attr-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        /* Menambahkan jarak antar tombol */
        .attr-nav li {
            margin-right: 20px;
        }

        /* Styling untuk notifikasi */
        #notifDropdown {
            display: flex;
            align-items: center;
            margin-right: 20px;
            justify-content: center;
            padding-top: 39px;
        }

        /* Styling untuk notifikasi badge */
        #notifBadge {
            position: absolute;
            right: 15px;
            top: 19px;
            transform: translate(40%, -45%);
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: -80%;
            font-size: 12px;
        }

        /* Styling untuk tombol search */
        .search a {
            color: #333;
            text-decoration: none;
        }

        /* Styling untuk tombol cart */
        .dropdown a {
            color: #333;
            margin-right: 20px;
            text-decoration: none;
        }

        /* Styling untuk banner-area */
        .banner-area {
            background: url('{{ asset('assets/images/slider/banner-bg.jpg') }}') no-repeat center center;
            background-size: cover;
            margin-right: -50px;
            padding: 110px 40px;
            color: #fff;
            text-align: center;
        }

        /* Styling untuk breadcrumb */
        .breadcrumb-banner {
            position: relative;
            z-index: 1;
        }

        .breadcrumb-banner .col-first h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .breadcrumb-banner .col-first nav {
            font-size: 16px;
            font-weight: 400;
        }

        .breadcrumb-banner .col-first nav a {
            color: black;
            text-decoration: none;
            margin-right: 5px;
        }

        .breadcrumb-banner .col-first nav a:hover {
            color: white;
        }

        .breadcrumb-banner .col-first nav .lnr {
            margin-left: 5px;
        }

        /* Flex styling for centering breadcrumb */
        .breadcrumb-banner.d-flex {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .breadcrumb-banner .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 767px) {
            .breadcrumb-banner .col-first h1 {
                font-size: 28px;
            }

            .breadcrumb-banner .col-first nav {
                font-size: 14px;
            }

            .breadcrumb-banner .col-first nav a {
                font-size: 14px;
            }
        }

        .order-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .order-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.10rem;
        }

        .order-card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .order-card-body {
            padding: 1.25rem;
        }

        .order-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .order-details-item {
            flex: 1;
            min-width: 200px;
        }

        .order-tracking-steps {
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px;
        }

        .order-tracking-steps .tracking-step {
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative;
        }

        .order-tracking-steps .tracking-step.active:before {
            background: #FF5722;
        }

        .order-tracking-steps .tracking-step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px;
        }

        .order-tracking-steps .tracking-step.active .tracking-icon {
            background: #ee5435;
            color: #fff;
        }

        .order-tracking-steps .tracking-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd;
        }

        .order-tracking-steps .tracking-step.active .tracking-text {
            font-weight: 400;
            color: #000;
        }

        .order-tracking-steps .tracking-text {
            display: block;
            margin-top: 7px;
        }

        .order-item-list {
            list-style: none;
            padding: 0;
        }

        .order-item {
            margin-bottom: 20px;
        }

        .order-item-figure {
            display: flex;
            margin-bottom: 15px;
        }

        .order-item-img {
            flex-shrink: 0;
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px;
        }

        .order-item-info {
            padding-left: 15px;
            padding-right: 7px;
        }

        .order-item-title {
            display: block;
            margin-bottom: 5px;
            color: #212529;
        }

        .order-item-price {
            color: #6c757d;
        }

        .order-btn {
            color: #ffffff;
            background-color: #ee5435;
            border-color: #ee5435;
            border-radius: 1px;
        }

        .order-btn:hover {
            color: #ffffff;
            background-color: #ff2b00;
            border-color: #ff2b00;
            border-radius: 1px;
        }
    </style>
</head>

<body>
    <!--welcome-hero start -->
    <header id="home" class="welcome-hero">

        <!-- top-area Start -->
        <div class="top-area">
            <div class="header-area">
                <!-- Start Navigation -->
                <nav class="navbar navbar-default bootsnav  navbar-sticky navbar-scrollspy"
                    data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">

                    <!-- Start Top Search -->
                    <div class="top-search">
                        <div class="container">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search">
                                <span style="color: black;" class="input-group-addon close-search"><i
                                        class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <!-- Start Atribute Navigation -->
                        <div class="attr-nav">
                            <ul>
                                <li class="search">
                                    <a href="#"><span class="lnr lnr-magnifier"></span></a>
                                </li><!--/.search-->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="lnr lnr-cart"></span>
                                        <span class="badge badge-bg-1">2</span>
                                    </a>
                                    <ul class="dropdown-menu cart-list s-cate">
                                        <li class="single-cart-list">
                                            <a href="#" class="photo"><img
                                                    src="{{ asset('assets/images/collection/arrivals1.png') }} "
                                                    class="cart-thumb" alt="image" /></a>
                                            <div class="cart-list-txt">
                                                <h6><a href="#">arm <br> chair</a></h6>
                                                <p>1 x - <span class="price">$180.00</span></p>
                                            </div>
                                            <div class="cart-close">
                                                <span class="lnr lnr-cross"></span>
                                            </div>
                                        </li>
                                        <li class="single-cart-list">
                                            <a href="#" class="photo"><img
                                                    src="{{ asset('assets/images/collection/arrivals2.png') }} "
                                                    class="cart-thumb" alt="image" /></a>
                                            <div class="cart-list-txt">
                                                <h6><a href="#">single <br> armchair</a></h6>
                                                <p>1 x - <span class="price">$180.00</span></p>
                                            </div>
                                            <div class="cart-close">
                                                <span class="lnr lnr-cross"></span>
                                            </div>
                                        </li>
                                        <li class="single-cart-list">
                                            <a href="#" class="photo"><img
                                                    src="{{ asset('assets/images/collection/arrivals3.png') }} "
                                                    class="cart-thumb" alt="image" /></a>
                                            <div class="cart-list-txt">
                                                <h6><a href="#">wooden arn <br> chair</a></h6>
                                                <p>1 x - <span class="price">$180.00</span></p>
                                            </div>
                                            <div class="cart-close">
                                                <span class="lnr lnr-cross"></span>
                                            </div>
                                        </li>
                                        <li class="total">
                                            <span>Total: Rp. 0.00</span>
                                            <button class="btn-cart pull-right"
                                                onclick="window.location.href='{{ route('cart.show') }}'">view
                                                cart</button>
                                        </li>

                                    </ul>
                                </li>
                                <!-- Notifikasi setelah cart -->
                                <li style="margin-top: -2px;" class="dropdown me-3">
                                    <button class="btn btn-outline-primary position-relative" type="button"
                                        id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-bell"></i> <!-- Ganti ikon fa-envelope menjadi fa-bell -->
                                        @if (session('success') || session('error'))
                                            <span id="notifBadge"
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                !
                                            </span>
                                        @endif
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown"
                                        style="min-width: 250px; max-height: 300px; overflow-y: auto;">
                                        @if (session('success'))
                                            <li><a class="dropdown-item text-success" href="#"><i
                                                        class="fas fa-check-circle me-2"></i>
                                                    {{ session('success') }}</a></li>
                                        @endif
                                        @if (session('error'))
                                            <li><a class="dropdown-item text-danger" href="#"><i
                                                        class="fas fa-times-circle me-2"></i>
                                                    {{ session('error') }}</a></li>
                                        @endif
                                        @if (!session('success') && !session('error'))
                                            <li><span class="dropdown-item text-muted">Tidak ada notifikasi
                                                    baru.</span></li>
                                        @endif
                                    </ul>
                                </li><!--/.notifikasi-->


                                <!-- Navigation bar with profile dropdown -->
                                <li class="nav-setting dropdown">
                                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        id="profileDropdown" aria-expanded="false">
                                        <!-- Menampilkan gambar profil pengguna -->
                                        <img src="{{ Auth::guard('customer')->user()->profile_image
                                            ? asset('storage/' . Auth::guard('customer')->user()->profile_image)
                                            : 'https://via.placeholder.com/40?text=IMG' }} "
                                            class="profile-img">
                                        <span
                                            class="ms-2">{{ Auth::guard('customer')->user()->name_customers }}</span>
                                        <!-- Menampilkan nama pengguna -->
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('customer.profile') }}">
                                                <i class="fa fa-user me-2"></i> <!-- Ikon untuk profil -->
                                                Lihat Profil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('seller.register.form') }}">
                                                <i class="fas fa-store me-2"></i>
                                                <!-- Ikon toko dengan warna hijau -->
                                                As a Seller
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('track.track') }}">
                                                <i class="fas fa-truck me-2"></i>
                                                Track Order
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button class="dropdown-item text-danger d-flex align-items-center"
                                                    type="submit">
                                                    <i class="fa fa-sign-out me-2"></i> <!-- Ikon untuk logout -->
                                                    Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                        <!-- Start Header Navigation -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <a class="navbar-brand" href="index.html">eco</a>
                        </div>
                        <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                            <ul class="nav navbar-nav" data-in="fadeInDown" data-out="fadeOutUp">
                                <li><a href="{{ route('customer.dashboard') }}">home</a></li>
                                <li><a href="{{ route('customer.dashboard') }}">new arrival</a></li>
                                <li><a href="{{ route('customer.dashboard') }}">features</a></li>
                                <li><a href="{{ route('customer.dashboard') }}">blog</a></li>
                                <li><a href="{{ route('customer.dashboard') }}">contact</a></li>
                            </ul>
                        </div>

                    </div>
                </nav>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1 style="color: black">Track Order</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('customer.dashboard') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('track.track') }}">Track Order</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="order-container">
        <article class="order-card">
            <header class="order-card-header"> My Orders / Tracking </header>
            <div class="order-card-body">
                <h6>Order ID: OD45345345435</h6>

                <!-- Grid Layout untuk detail pengiriman -->
                <div class="order-details">
                    <div class="order-details-item">
                        <strong>Estimated Delivery time:</strong> <br>29 nov 2019
                    </div>
                    <div class="order-details-item">
                        <strong>Shipping BY:</strong> <br> BLUEDART, | <i class="fa fa-phone"></i> +1598675986
                    </div>
                    <div class="order-details-item">
                        <strong>Status:</strong> <br> Picked by the courier
                    </div>
                    <div class="order-details-item">
                        <strong>Tracking #:</strong> <br> BD045903594059
                    </div>
                </div>

                <!-- Tracking Steps -->
                <div class="order-tracking-steps">
                    <div class="tracking-step active">
                        <span class="tracking-icon">
                            <i class="fa fa-check"></i>
                        </span>
                        <span class="tracking-text">Order confirmed</span>
                    </div>
                    <div class="tracking-step active">
                        <span class="tracking-icon">
                            <i class="fa fa-user"></i>
                        </span>
                        <span class="tracking-text">Picked by courier</span>
                    </div>
                    <div class="tracking-step">
                        <span class="tracking-icon">
                            <i class="fa fa-truck"></i>
                        </span>
                        <span class="tracking-text">On the way</span>
                    </div>
                    <div class="tracking-step">
                        <span class="tracking-icon">
                            <i class="fa fa-box"></i>
                        </span>
                        <span class="tracking-text">Ready for pickup</span>
                    </div>
                </div>

                <hr>

                <ul class="order-item-list row">
                    <li class="order-item col-md-4">
                        <figure class="order-item-figure mb-3">
                            <div class="order-item-img">
                                <img src="{{ asset('assets/images/collection/Tas Kemasan Kopi.jpg') }}" class="img-sm border">
                            </div>
                            <figcaption class="order-item-info align-self-center">
                                <p class="order-item-title">Tas Kemasan Kopi <br> 1</p>
                                <span class="order-item-price text-muted">Rp. 100.000</span>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="order-item col-md-4">
                        <figure class="order-item-figure mb-3">
                            <div class="order-item-img">
                                <img src="{{ asset('assets/images/collection/plastic-spoon-lamp.jpg') }}" class="img-sm border">
                            </div>
                            <figcaption class="order-item-info align-self-center">
                                <p class="order-item-title">Lampu Sendok Plastik <br> 1</p>
                                <span class="order-item-price text-muted">Rp. 100.000</span>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="order-item col-md-4">
                        <figure class="order-item-figure mb-3">
                            <div class="order-item-img">
                                <img src="{{ asset('assets/images/collection/perahu-botol.png') }}" class="img-sm border">
                            </div>
                            <figcaption class="order-item-info align-self-center">
                                <p class="order-item-title">Perahu Botol <br> 1</p>
                                <span class="order-item-price text-muted">Rp. 70.000</span>
                            </figcaption>
                        </figure>
                    </li>
                </ul>

                <hr>
                <a href="#" class="order-btn btn btn-warning" data-abc="true">
                    <i class="fa fa-chevron-left"></i> Back to orders
                </a>
            </div>
        </article>
    </div>

    <!-- Footer -->
    <footer id="footer" class="footer">
        <div class="container">
            <div class="hm-footer-copyright text-center">
                <div class="footer-social">
                    <a href="https://www.facebook.com/shandy.s.shihab"><i class="fa fa-facebook"></i></a>
                    <a href="https://www.instagram.com/shandyshihab/"><i class="fa fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/shandy-shulton-shihab-73a25922a/"><i class="fa fa-linkedin"></i></a>
                </div>
                <p>
                    &copy;copyright. designed and developed by <a href="https://www.linkedin.com/in/shandy-shulton-shihab-73a25922a/">EcoCraft</a>
                </p>
            </div>
        </div>
        <div id="scroll-Top">
            <div class="return-to-top">
                <i class="fa fa-angle-up " id="scroll-top" data-toggle="tooltip" data-placement="top"
                    title="" data-original-title="Back to Top" aria-hidden="true"></i>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootsnav.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
