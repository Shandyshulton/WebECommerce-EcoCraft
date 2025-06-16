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

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .col-md-6 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .col-md-12 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        @media (max-width: 767px) {
            .col-md-6 {
                margin-bottom: 15px;
            }
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
                    <h1 style="color: black">Checkout</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('customer.dashboard') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('cart.show') }}">Checkout</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Start Hero Section -->
    <div style="margin-top: 50px;" class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Checkout</h1>
                    </div>
                </div>
                <div class="col-lg-7">

                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="border p-4 rounded" role="alert">
                        Returning customer? <a href="#">Click here</a> to login
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        <div class="form-group">
                            <label for="c_country" class="text-black">Country <span
                                    class="text-danger">*</span></label>
                            <select id="c_country" class="form-control">
                                <option value="1">Select a country</option>
                                <option value="2">bangladesh</option>
                                <option value="3">Algeria</option>
                                <option value="4">Afghanistan</option>
                                <option value="5">Ghana</option>
                                <option value="6">Albania</option>
                                <option value="7">Bahrain</option>
                                <option value="8">Colombia</option>
                                <option value="9">Dominican Republic</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_fname" class="text-black">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_fname" name="c_fname">
                            </div>
                            <div class="col-md-6">
                                <label for="c_lname" class="text-black">Last Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_lname" name="c_lname">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_companyname" class="text-black">Company Name </label>
                                <input type="text" class="form-control" id="c_companyname" name="c_companyname">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_address" name="c_address"
                                    placeholder="Street address">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <input type="text" class="form-control"
                                placeholder="Apartment, suite, unit etc. (optional)">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_state_country" class="text-black">State / Country <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_state_country"
                                    name="c_state_country">
                            </div>
                            <div class="col-md-6">
                                <label for="c_postal_zip" class="text-black">Posta / Zip <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip">
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="c_email_address" class="text-black">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_email_address"
                                    name="c_email_address">
                            </div>
                            <div class="col-md-6">
                                <label for="c_phone" class="text-black">Phone <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_phone" name="c_phone"
                                    placeholder="Phone Number">
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="collapse" id="create_an_account">
                                <div class="py-2 mb-4">
                                    <p class="mb-3">Create an account by entering the information below. If you are a
                                        returning customer please login at the top of the page.</p>
                                    <div class="form-group">
                                        <label for="c_account_password" class="text-black">Account Password</label>
                                        <input type="email" class="form-control" id="c_account_password"
                                            name="c_account_password" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="c_ship_different_address" class="text-black" data-bs-toggle="collapse"
                                href="#ship_different_address" role="button" aria-expanded="false"
                                aria-controls="ship_different_address"><input type="checkbox" value="1"
                                    id="c_ship_different_address"> Ship To A Different Address?</label>
                            <div class="collapse" id="ship_different_address">
                                <div class="py-2">

                                    <div class="form-group">
                                        <label for="c_diff_country" class="text-black">Country <span
                                                class="text-danger">*</span></label>
                                        <select id="c_diff_country" class="form-control">
                                            <option value="1">Select a country</option>
                                            <option value="2">bangladesh</option>
                                            <option value="3">Algeria</option>
                                            <option value="4">Afghanistan</option>
                                            <option value="5">Ghana</option>
                                            <option value="6">Albania</option>
                                            <option value="7">Bahrain</option>
                                            <option value="8">Colombia</option>
                                            <option value="9">Dominican Republic</option>
                                        </select>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="c_diff_fname" class="text-black">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_fname"
                                                name="c_diff_fname">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="c_diff_lname" class="text-black">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_lname"
                                                name="c_diff_lname">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="c_diff_companyname" class="text-black">Company Name </label>
                                            <input type="text" class="form-control" id="c_diff_companyname"
                                                name="c_diff_companyname">
                                        </div>
                                    </div>

                                    <div class="form-group row  mb-3">
                                        <div class="col-md-12">
                                            <label for="c_diff_address" class="text-black">Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_address"
                                                name="c_diff_address" placeholder="Street address">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control"
                                            placeholder="Apartment, suite, unit etc. (optional)">
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="c_diff_state_country" class="text-black">State / Country <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_state_country"
                                                name="c_diff_state_country">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="c_diff_postal_zip" class="text-black">Posta / Zip <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_postal_zip"
                                                name="c_diff_postal_zip">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-5">
                                        <div class="col-md-6">
                                            <label for="c_diff_email_address" class="text-black">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_email_address"
                                                name="c_diff_email_address">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="c_diff_phone" class="text-black">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="c_diff_phone"
                                                name="c_diff_phone" placeholder="Phone Number">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="c_order_notes" class="text-black">Order Notes</label>
                            <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control"
                                placeholder="Write your notes here..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                            <div class="p-3 p-lg-5 border bg-white">

                                <label for="c_code" class="text-black mb-3">Enter your coupon code if you have
                                    one</label>
                                <div class="input-group w-75 couponcode-wrap">
                                    <input type="text" class="form-control me-2" id="c_code"
                                        placeholder="Coupon Code" aria-label="Coupon Code"
                                        aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-black btn-sm" type="button"
                                            id="button-addon2">Apply</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Top Up T-Shirt <strong class="mx-2">x</strong> 1</td>
                                            <td>$250.00</td>
                                        </tr>
                                        <tr>
                                            <td>Polo Shirt <strong class="mx-2">x</strong> 1</td>
                                            <td>$100.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                            <td class="text-black">$350.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                            <td class="text-black font-weight-bold"><strong>$350.00</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse"
                                            href="#collapsebank" role="button" aria-expanded="false"
                                            aria-controls="collapsebank">Direct Bank Transfer</a></h3>

                                    <div class="collapse" id="collapsebank">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please
                                                use your Order ID as the payment reference. Your order won’t be shipped
                                                until the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse"
                                            href="#collapsecheque" role="button" aria-expanded="false"
                                            aria-controls="collapsecheque">Cheque Payment</a></h3>

                                    <div class="collapse" id="collapsecheque">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please
                                                use your Order ID as the payment reference. Your order won’t be shipped
                                                until the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border p-3 mb-5">
                                    <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse"
                                            href="#collapsepaypal" role="button" aria-expanded="false"
                                            aria-controls="collapsepaypal">Paypal</a></h3>

                                    <div class="collapse" id="collapsepaypal">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account. Please
                                                use your Order ID as the payment reference. Your order won’t be shipped
                                                until the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-black btn-lg py-3 btn-block"
                                        onclick="window.location='thankyou.html'">Place Order</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- </form> -->
        </div>
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
