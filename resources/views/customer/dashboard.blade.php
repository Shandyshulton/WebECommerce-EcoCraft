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
    </style>
</head>

<body>
    <!--welcome-hero start -->
    <header id="home" class="welcome-hero">

        <div id="header-carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <!--/.carousel-indicator -->
            <ol class="carousel-indicators">
                <li data-target="#header-carousel" data-slide-to="0" class="active"><span class="small-circle"></span>
                </li>
                <li data-target="#header-carousel" data-slide-to="1"><span class="small-circle"></span></li>
                <li data-target="#header-carousel" data-slide-to="2"><span class="small-circle"></span></li>
            </ol>

            <!--/.carousel-inner -->
            <div class="carousel-inner" role="listbox">
                <!-- .item -->
                <div class="item active">
                    <div class="single-slide-item slide1">
                        <div class="container">
                            <div class="welcome-hero-content">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-txt">
                                                <h4>great design collection</h4>
                                                <h2>PaperPerch</h2>
                                                <p>
                                                    Embrace the Future of Sustainable Design with Our Unique Cardboard
                                                    Chairs – Combining Comfort, Durability, and Eco-Friendly Materials
                                                    to Elevate Any Space.
                                                </p>
                                                <div class="packages-price">
                                                    <p>
                                                        Rp. 150.000
                                                        <del>Rp. 200.000</del>
                                                    </p>
                                                </div>
                                                <button class="btn-cart welcome-add-cart"
                                                    onclick="window.location.href='{{ route('cart.show') }}'">
                                                    <span class="lnr lnr-plus-circle"></span>
                                                    add <span>to</span> cart
                                                </button>

                                                <button class="btn-cart welcome-add-cart welcome-more-info"
                                                    onclick="window.location.href='#'">
                                                    more info
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-img">
                                                <img src="{{ asset('assets/images/slider/kursi kardus.png') }} "
                                                    alt="slider image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="single-slide-item slide2">
                        <div class="container">
                            <div class="welcome-hero-content">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-txt">
                                                <h4>great design collection</h4>
                                                <h2>Newspaper Couture</h2>
                                                <p>
                                                    Step into a world of sustainable fashion with Newspaper Couture—a
                                                    stunning piece of wearable art made entirely from recycled
                                                    newspaper. This eco-friendly dress blends creativity with
                                                    sustainability, featuring layers of textured newsprint that form an
                                                    elegant, avant-garde silhouette. Perfect for those who want to make
                                                    a bold fashion statement while promoting environmental
                                                    consciousness. Embrace the future of fashion—where style meets
                                                    sustainability!
                                                </p>
                                                <div class="packages-price">
                                                    <p>
                                                        Rp. 200.000
                                                        <del>Rp. 250.000</del>
                                                    </p>
                                                </div>
                                                <button class="btn-cart welcome-add-cart"
                                                    onclick="window.location.href='{{ route('cart.show') }}'">
                                                    <span class="lnr lnr-plus-circle"></span>
                                                    add <span>to</span> cart
                                                </button>

                                                <button class="btn-cart welcome-add-cart welcome-more-info"
                                                    onclick="window.location.href='#'">
                                                    more info
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-img">
                                                <img src="{{ asset('assets/images/slider/Gaun Koran.png') }} "
                                                    alt="slider image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="single-slide-item slide3">
                        <div class="container">
                            <div class="welcome-hero-content">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-txt">
                                                <h4>great design collection</h4>
                                                <h2>Spoon Glow</h2>
                                                <p>
                                                    Illuminate your space with Spoon Glow, an innovative pendant lamp
                                                    crafted from recycled plastic spoons. Its unique, scale-like design
                                                    creates a stunning visual effect when lit, casting a soft, inviting
                                                    glow perfect for modern, eco-conscious interiors. A conversation
                                                    starter and an eco-friendly statement piece, Spoon Glow brings
                                                    creativity and sustainability together, transforming your room into
                                                    a work of art while reducing plastic waste. Ideal for those looking
                                                    to add a touch of whimsical, yet elegant design to their home or
                                                    office.
                                                </p>
                                                <div class="packages-price">
                                                    <p>
                                                        Rp. 199.00
                                                        <del>Rp. 299.000</del>
                                                    </p>
                                                </div>
                                                <button class="btn-cart welcome-add-cart"
                                                    onclick="window.location.href='{{ route('cart.show') }}'">
                                                    <span class="lnr lnr-plus-circle"></span>
                                                    add <span>to</span> cart
                                                </button>

                                                <button class="btn-cart welcome-add-cart welcome-more-info"
                                                    onclick="window.location.href='#'">
                                                    more info
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="single-welcome-hero">
                                            <div class="welcome-hero-img">
                                                <img src="{{ asset('assets/images/slider/lampu sendok plastik.png') }} "
                                                    alt="slider image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
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
                                <li class="scroll active"><a href="#home">home</a></li>
                                <li class="scroll"><a href="#new-arrivals">new arrival</a></li>
                                <li class="scroll"><a href="#feature">features</a></li>
                                <li class="scroll"><a href="#blog">blog</a></li>
                                <li class="scroll"><a href="#newsletter">contact</a></li>
                            </ul>
                        </div>

                    </div>
                </nav>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>

    <!--populer-products start -->
    <section id="populer-products" class="populer-products">
        <div class="container">
            <div class="populer-products-content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="single-populer-products">
                            <div class="single-populer-product-img mt40">
                                <img src="{{ asset('assets/images/populer-products/kursi ban.png') }}"
                                    alt="populer-products images">
                            </div>
                            <h2><a href="#">arm chair</a></h2>
                            <div class="single-populer-products-para">
                                <p>Eco-friendly chairs made from repurposed tires.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="single-populer-products">
                            <div class="single-inner-populer-products">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="single-inner-populer-product-img">
                                            <img src="{{ asset('assets/images/populer-products/vas bunga.png') }}"
                                                alt="populer-products images">
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="single-inner-populer-product-txt">
                                            <h2>
                                                <a href="#">
                                                    handicrafts from <span>Used</span> materials
                                                </a>
                                            </h2>
                                            <p>
                                                Creative flower vases made from repurposed light bulbs, perfect for
                                                adding a touch of greenery to any space.
                                            </p>
                                            <div class="populer-products-price">
                                                <h4>Sales Start from <span>50.000</span></h4>
                                            </div>
                                            <button class="btn-cart welcome-add-cart populer-products-btn"
                                                onclick="window.location.href='#'">
                                                discover more
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="single-populer-products">
                            <div class="single-populer-products">
                                <div class="single-populer-product-img">
                                    <img src="{{ asset('assets/images/populer-products/hanginglamp.png') }}"
                                        alt="populer-products images">
                                </div>
                                <h2><a href="#">hanging lamp</a></h2>
                                <div class="single-populer-products-para">
                                    <p>Colorful hanging lamps made from recycled glass bottles.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-header">
        <h2>New Arrival</h2>
    </div><!--/.section-header-->
    <!--new-arrivals start -->
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 col-sm-3">
                <div class="single-new-arrival">
                    <div class="single-new-arrival-bg">
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}">
                        <div class="single-new-arrival-bg-overlay"></div>
                        <div class="sale bg-1">
                            <p>sale</p>
                        </div>
                        <div class="new-arrival-cart">
                            <p>
                                <span class="lnr lnr-cart"></span>
                                <a href="{{ route('cart.show') }}">add <span>to </span> cart</a>

                            </p>
                            <p class="arrival-review pull-right">
                                <span class="lnr lnr-heart"></span>
                                <a href="{{ route('product.show', $product->id_products) }}">
                                    <span class="lnr lnr-frame-expand"></span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <h4><a href="#">{{ $product->name }}</a></h4>
                    <p class="arrival-product-price">Rp. {{ number_format($product->price, 2) }}</p>
                </div>
            </div>
        @empty
            <p>Tidak ada produk tersedia.</p>
        @endforelse
    </div>

    <!-- banner-collection start -->
    <section id="banner-collection">
        <div class="banner-carousel">
            <div class="banner-slide">
                <img src="{{ asset('assets/images/collection/perahu-botol.png') }} " alt="Banner 1">
            </div>
            <div class="banner-slide">
                <img src="{{ asset('assets/images/collection/banner welcome.png') }} " alt="Banner 2">
            </div>
            <div class="banner-slide">
                <img src="{{ asset('assets/images/collection/delivery.png') }} " alt="Banner 3">
            </div>
        </div>
    </section>

    <!--feature start -->
    <section id="feature" class="feature">
        <div class="container">
            <div class="section-header">
                <h2>featured products</h2>
            </div><!--/.section-header-->
            <div class="feature-content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="single-feature">
                            <img src="{{ asset('assets/images/features/f1.jpg') }}" alt="feature image">
                            <div class="single-feature-txt text-center">
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="spacial-feature-icon"><i class="fa fa-star"></i></span>
                                    <span class="feature-review">(45 review)</span>
                                </p>
                                <h3><a href="#">designed sofa</a></h3>
                                <h5>$160.00</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="single-feature">
                            <img src="{{ asset('assets/images/features/f2.jpg') }}" alt="feature image">
                            <div class="single-feature-txt text-center">
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="spacial-feature-icon"><i class="fa fa-star"></i></span>
                                    <span class="feature-review">(45 review)</span>
                                </p>
                                <h3><a href="#">dinning table </a></h3>
                                <h5>$200.00</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="single-feature">
                            <img src="{{ asset('assets/images/features/f3.jpg') }}" alt="feature image">
                            <div class="single-feature-txt text-center">
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="spacial-feature-icon"><i class="fa fa-star"></i></span>
                                    <span class="feature-review">(45 review)</span>
                                </p>
                                <h3><a href="#">chair and table</a></h3>
                                <h5>$100.00</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="single-feature">
                            <img src="{{ asset('assets/images/features/f4.jpg') }}" alt="feature image">
                            <div class="single-feature-txt text-center">
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="spacial-feature-icon"><i class="fa fa-star"></i></span>
                                    <span class="feature-review">(45 review)</span>
                                </p>
                                <h3><a href="#">modern arm chair</a></h3>
                                <h5>$299.00</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--blog start -->
    <section id="blog" class="blog">
        <div class="container">
            <div class="section-header">
                <h2>latest blog</h2>
            </div>
            <div class="blog-content">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="single-blog">
                            <div class="single-blog-img">
                                <img src="{{ asset('assets/images/blog/b1.jpg') }}" alt="blog image">
                                <div class="single-blog-img-overlay"></div>
                            </div>
                            <div class="single-blog-txt">
                                <h2><a href="#">Why Brands are Looking at Local Language</a></h2>
                                <h3>By <a href="#">Robert Norby</a> / 18th March 2018</h3>
                                <p>
                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                    consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt....
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="single-blog">
                            <div class="single-blog-img">
                                <img src="{{ asset('assets/images/blog/b2.jpg') }}" alt="blog image">
                                <div class="single-blog-img-overlay"></div>
                            </div>
                            <div class="single-blog-txt">
                                <h2><a href="#">Why Brands are Looking at Local Language</a></h2>
                                <h3>By <a href="#">Robert Norby</a> / 18th March 2018</h3>
                                <p>
                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                    consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt....
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="single-blog">
                            <div class="single-blog-img">
                                <img src="{{ asset('assets/images/blog/b3.jpg') }}" alt="blog image">
                                <div class="single-blog-img-overlay"></div>
                            </div>
                            <div class="single-blog-txt">
                                <h2><a href="#">Why Brands are Looking at Local Language</a></h2>
                                <h3>By <a href="#">Robert Norby</a> / 18th March 2018</h3>
                                <p>
                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                    consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt....
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- clients strat -->
    <section id="clients" class="clients">
        <div class="container">
            <div class="owl-carousel owl-theme" id="client">
                <div class="item">
                    <a href="#">
                        <img src="{{ asset('assets/images/clients/brand1.png') }}" alt="brand-image" />
                    </a>
                </div>
                <div class="item">
                    <a href="#">
                        <img src="{{ asset('assets/images/clients/brand 2.png') }}" alt="brand-image" />
                    </a>
                </div>
                <div class="item">
                    <a href="#">
                        <img src="{{ asset('assets/images/clients/brand 3.png') }}" alt="brand-image" />
                    </a>
                </div>
                <div class="item">
                    <a href="#">
                        <img src="{{ asset('assets/images/clients/brand 4.png') }}" alt="brand-image" />
                    </a>
                </div>
                <div class="item">
                    <a href="#">
                        <img src="{{ asset('assets/images/clients/brand 5.png') }}" alt="brand-image" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--newsletter strat -->
    <section id="newsletter" class="newsletter">
        <div class="container">
            <div class="hm-footer-details">
                <div class="row">
                    <div class=" col-md-3 col-sm-6 col-xs-12">
                        <div class="hm-footer-widget">
                            <div class="hm-foot-title">
                                <h4>information</h4>
                            </div>
                            <div class="hm-foot-menu">
                                <ul>
                                    <li><a href="#">about us</a></li>
                                    <li><a href="#">contact us</a></li>
                                    <li><a href="#">news</a></li>
                                    <li><a href="#">store</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-3 col-sm-6 col-xs-12">
                        <div class="hm-footer-widget">
                            <div class="hm-foot-title">
                                <h4>my accounts</h4>
                            </div>
                            <div class="hm-foot-menu">
                                <ul>
                                    <li><a href="{{ route('customer.profile') }}">my account</a></li>
                                    <li><a href="{{ route('track.track') }}">order history</a></li>
                                    <li><a href="{{ route('cart.show') }}">my cart</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-3 col-sm-6  col-xs-12">
                        <div class="hm-footer-widget">
                            <div class="hm-foot-title">
                                <h4>newsletter</h4>
                            </div>
                            <div class="hm-foot-para">
                                <p>
                                    Subscribe to get latest news,update and information.
                                </p>
                            </div>
                            <div class="hm-foot-email">
                                <div class="foot-email-box">
                                    <input type="text" class="form-control" placeholder="Enter Email Here....">
                                </div>
                                <div class="foot-email-subscribe">
                                    <span><i class="fa fa-location-arrow"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.banner-slide');
        const totalSlides = slides.length;

        function changeSlide() {
            currentIndex = (currentIndex + 1) % totalSlides; // Menyusun gambar agar terus berganti
            const offset = -100 * currentIndex; // Geser ke gambar berikutnya
            document.querySelector('.banner-carousel').style.transform = `translateX(${offset}%)`;
        }
        setInterval(changeSlide, 15000); // Mengganti gambar setiap 15 detik
    </script>
</body>

</html>
