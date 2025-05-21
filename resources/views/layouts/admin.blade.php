<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <title>SIG WiFi Publik Kab. Garut</title>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Admin Panel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #58a6d5, #4e4376);
            position: fixed;
            top: 0;
            left: 0;
            color: #fff;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 15px 20px;
            transition: background 0.3s, transform 0.2s;
            border-radius: 5px;
            margin: 5px;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .navbar {
            height: 60px;
            background-color: #fff;
            border-bottom: 2px solid #ddd;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease-in-out;
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .navbar a:hover {
            background: #f0f0f0;
        }

        .navbar a i {
            margin-right: 8px;
        }

        .menu-btn {
            display: none;
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            position: absolute;
            left: 10px;
            color: #333;
        }

        .content {
            margin-left: 250px;
            padding: 80px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .menu-btn {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .navbar {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>

    <script>
        function confirmValidation(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengaduan ini akan divalidasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Validasi!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('validasi-form-' + id).submit();
                }
            });
        }

        function confirmTolak(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengaduan ini akan ditolak.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('tolak-form-' + id).submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "WiFi ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3>Admin Panel</h3>
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('admin.peta') }}"><i class="fas fa-map"></i> WiFi</a>
        <a href="{{ route('admin.pengaduan') }}"><i class="fas fa-exclamation-circle"></i> Pengaduan</a>
    </div>

    <!-- Navbar -->
    <div class="navbar">
        <button class="menu-btn" id="menu-btn"><i class="fas fa-bars"></i></button>
        <a href="{{ route('logout') }}" class="logout-btn"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script>
        // Sidebar toggle
        document.getElementById('menu-btn').addEventListener('click', function (event) {
            event.stopPropagation();
            document.getElementById('sidebar').classList.toggle('active');
        });

        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuBtn = event.target.closest('#menu-btn') !== null;

            if (!isClickInsideSidebar && !isClickOnMenuBtn && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
