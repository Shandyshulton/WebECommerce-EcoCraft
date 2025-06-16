<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Craft</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0077B6;
            color: white;
            position: fixed;
            padding: 20px;
            transition: transform 0.3s ease-in-out;
            z-index: 1001;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 16px;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background: #2196C5;
        }

        .logout-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            color: white;
            padding: 12px 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-btn i {
            margin-right: 10px;
            font-size: 18px;
        }

        .logout-btn:hover {
            background: #D9534F;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            position: fixed;
            width: calc(100% - 250px);
            left: 250px;
            top: 0;
            z-index: 1002;
            transition: left 0.3s ease-in-out;
        }

        .navbar-custom .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .content {
            margin-left: 250px;
            padding: 80px 20px 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 24px;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-menu {
            right: 0;
            left: auto;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .navbar-custom {
                width: 100%;
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            .hamburger {
                display: block;
            }

            .overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay"></div>

    <div class="sidebar" id="sidebar">
        <h4>Eco Craft</h4>
        <a href="/" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="{{ route('admin.products.verify') }}"><i class="fas fa-user-check"></i> Verifikasi Product</a>
        <a href="{{ route('admin.sellers.verify') }}"><i class="fas fa-user-check"></i> Verifikasi Seller</a>
        <a href="#"><i class="fas fa-user-tie"></i> Staff</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <nav class="navbar navbar-custom">
        <div class="navbar-content">
            <button class="hamburger" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <span id="current-date" class="ms-3"></span>

            <div class="dropdown">
                <img
                    src="{{ asset('storage/' . Auth::guard('admin')->user()->profile_image) }}"
                    alt="Admin Profile"
                    class="profile-img dropdown-toggle"
                    id="profileDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    role="button">
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-item-text fw-bold">
                        {{ Auth::guard('admin')->user()->name }}
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
    </nav>

    <div class="content">
        {{-- Breadcrumb Section --}}
        @if (View::hasSection('breadcrumb'))
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        @yield('content')
    </div>

    <script>
        document.getElementById('current-date').innerText = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById("sidebarToggle").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("active");
            document.getElementById("overlay").classList.toggle("active");
        });

        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("sidebar").classList.remove("active");
            this.classList.remove("active");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
