<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIG WiFi Publik Kab. Garut</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Tambahkan Font Awesome untuk Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Tambahkan Google Fonts untuk Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Custom CSS untuk responsif */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #58a6d5; /* Warna latar belakang biru */
        }

        #app {
            height: 100%;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .card {
            width: 100%;
            max-width: 500px; /* Lebar maksimum card */
            margin: 0 auto; /* Pusatkan card */
        }

        .input-group-text {
            background-color: #e0f2f7;
            color: #58a6d5;
            border: none;
        }

        .btn-primary {
            background-color: #58a6d5;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4a8bb8;
        }

        @media (max-width: 576px) {
            .card {
                border-radius: 0; /* Hilangkan border radius pada layar kecil */
            }
            h4 {
                font-size: 1.5rem; /* Ukuran font lebih kecil untuk mobile */
            }
            p {
                font-size: 0.9rem; /* Ukuran font lebih kecil untuk mobile */
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
