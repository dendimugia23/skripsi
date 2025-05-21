<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SIG WiFi Publik Kab. Garut</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ secure_asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ secure_asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ secure_asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ secure_asset('assets/css/main.css') }}" rel="stylesheet">

</head>
<body class="index-page">

  <!-- Header -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#hero" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">SIG WiFi Publik</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#map">Lokasi</a></li>
          <li><a href="#tentang">Tentang Wifi Publik</a></li>
          <li><a href="#panduan">Panduan</a></li>
          <li><a href="#faq">FAQs</a></li>
          <li class="dropdown">
            <a href="{{ route('pengaduan.user') }}"><span>Pengaduan</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{ route('pengaduan.user') }}">Buat Pengaduan</a></li>
              <li><a href="#search">Cari Pengaduan</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    @include('hero')
    @include('map')
    @include('tentang')
    @include('panduan')
    @include('faq')
    @include('caripengaduan')
  </main>

  <!-- Footer -->
  <footer id="footer" class="footer position-relative light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="#" class="logo d-flex align-items-center">
            <span class="sitename">SIG WIFI PUBLIK</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Dinas Komunikasi dan Informatika Kabupaten Garut</p>
            <p>Jl. Pembangunan No.181, Sukagalih, Kec. Tarogong Kidul, Kabupaten Garut, Jawa Barat 44151</p>
            <p class="mt-3"><strong>Telepon:</strong> <span>(0262) 2808994</span></p>
            <p><strong>Email:</strong> <span>diskominfo@garutkab.go.id</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="https://www.instagram.com/diskominfogrt/" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="https://diskominfo.garutkab.go.id/" target="_blank"><i class="bi bi-globe"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span> <strong class="px-1 sitename">QuickStart</strong><span> All Rights Reserved</span></p>
    <div class="credits">
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </div>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ secure_asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ secure_asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ secure_asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ secure_asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ secure_asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ secure_asset('assets/js/main.js') }}"></script>

</body>
</html>
