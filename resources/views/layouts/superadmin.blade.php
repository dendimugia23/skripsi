<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Admin Panel') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Load Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script>
        function validateRejection(form) {
            const komentar = form.komentar.value.trim();
            if (!komentar) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Harap berikan alasan penolakan sebelum mengirim!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }
    </script>
    <script>
        function konfirmasiValidasi(id) {
            Swal.fire({
                title: 'Setujui WiFi Ini?',
                text: "Data akan divalidasi dan ditampilkan di peta.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Validasi!',
                cancelButtonText: 'Batal',
                reverseButtons: 'true'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-validasi-' + id).submit();
                }
            });
        }
    </script>
    
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
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3>Super Admin Panel</h3>
        <a href="{{ route('superadmin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('superadmin.peta') }}"><i class="fas fa-map"></i> WiFi</a>
        <a href="{{ route('superadmin.pengaduan') }}"><i class="fas fa-exclamation-circle"></i> Pengaduan</a>
        <!-- New Rekapitulasi Menu Item -->
        <a href="{{ route('superadmin.rekapitulasi') }}"><i class="fas fa-clipboard-list"></i> Rekapitulasi</a>
    </div>

    <!-- Navbar -->
    <div class="navbar">
        <button class="menu-btn" id="menu-btn"><i class="fas fa-bars"></i></button>
        <a href="{{ route('logout') }}" class="logout-btn" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script>
        document.getElementById('menu-btn').addEventListener('click', function (event) {
            event.stopPropagation(); // Prevent the event from bubbling to the document
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
