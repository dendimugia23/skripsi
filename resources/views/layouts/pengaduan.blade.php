<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIG WiFi Publik Kab. Garut</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- App CSS & JS -->
    <link rel="stylesheet" href="{{ secure_asset('build/assets/app-BaVMVknW.css') }}">
    <script type="module" src="{{ secure_asset('build/assets/app-e9C80sKX.js') }}"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #58a6d5;
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

        .form-select {
            font-size: 14px;
            padding: 10px;
        }

        .card {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
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
                border-radius: 0;
            }
            h4 {
                font-size: 1.5rem;
            }
            p {
                font-size: 0.9rem;
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
